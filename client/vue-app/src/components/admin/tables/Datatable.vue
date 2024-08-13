<template>
  <v-data-table v-model="selected" :search="search" show-select :loading="loading" :headers="headers"
    :items="collection"
    :sort-by="store.$id !== 'invoiceCompanyStore' ? [{ key: 'name', order: 'asc' }, { key: 'country', order: 'asc' }, { key: 'company', order: 'asc' }] : [{ key: 'company', order: 'asc' }]">
    <template v-if="ifHyperlink" v-slot:item.name="{ item }">
      <router-link :to="{ name: 'Models', params: { id: item.id, name: item.name } }">
        {{ item.name }}
      </router-link>
    </template>


    <template v-slot:top>
      <v-toolbar color="#071d35" class="px-3" flat>
        <v-btn prepend-icon="mdi-file-table-outline" @click="exportItems" color="#66BB6A" variant="flat"
          class="mx-2">Export</v-btn>
        <v-spacer></v-spacer>
        <v-spacer></v-spacer>


        <v-btn prepend-icon="mdi-trash-can-outline" @click="deleteItem()" color="#EF5350" variant="flat">
          Delete</v-btn>

        <v-dialog v-model="dialog" width="600px">
          <template v-slot:activator="{ props }">
            <v-btn prepend-icon=" mdi-plus" color="#00ADB5" class="mx-3" variant="flat" v-bind="props">
              New
            </v-btn>
          </template>

          <v-card>

            <v-card-title class="d-flex align-center mb-0 pb-0">
              <span class="text-h6 text-uppercase">{{ formTitle }}</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-container>
              <v-card-text>
                <v-form ref="form">
                  <v-row>
                    <v-col class="d-flex justify-center" v-for="field in fields" :key="field.name" :cols="field.cols"
                      :md="field.md" :sm="field.sm" :offset="field.offset">
                      <v-text-field v-model="editedItemData[field.name]" :prepend-inner-icon="field.icon"
                        v-if="field.type === 'text' || field.type === 'number'" :type="field.type" :label="field.label"
                        :rules="field.rules" :error-messages="errors[field.name]"></v-text-field>

                      <v-textarea v-if="field.type === 'textarea'" rows="2" :prepend-inner-icon="field.icon"
                        :rules="[rules.required]" v-model="editedItemData[field.name]"
                        :label="field.label"></v-textarea>


                      <v-combobox v-if="field.type === 'combobox'" :label="field.label" :items="countriesList"
                        :rules="field.rules" v-model="editedItemData[field.name]"
                        :error-messages="errors[field.name]"></v-combobox>

                      <ImageUpload :image="image" v-if="field.type === 'image'" @imageData="handleImageData" />
                    </v-col>
                    <v-col> </v-col>
                  </v-row>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" variant="outlined" @click="close">
                  Cancel
                </v-btn>
                <v-btn color="primary" :loading="loading" variant="flat" @click="save">
                  Save
                </v-btn>
              </v-card-actions>
            </v-container>
          </v-card>
        </v-dialog>


        <DeleteDialog v-model="dialogDelete" :loading="loading" :item="store.name" type="delete" @close="closeDelete"
          @confirm="deleteItemConfirm" />
        <v-text-field v-model="search" prepend-inner-icon="mdi-magnify" density="compact" label="Search" rounded
          single-line flat hide-details variant="solo-filled">
        </v-text-field>
      </v-toolbar>
    </template>

    <template v-slot:item.actions="{ item }">
      <v-icon size="small" class="me-2" @click="editItem(item)">
        mdi-pencil
      </v-icon>
    </template>

    <template v-slot:no-data>
      <v-btn color="primary" @click="initialize"> Reset </v-btn>
    </template>
  </v-data-table>
</template>

<script setup>
import { storeToRefs } from "pinia";
import { computed, nextTick, ref, watch, defineProps } from "vue";
import { rules } from "/src/includes/customValidationRules.js";
import ImageUpload from "../../base/form-elements/ImageUpload.vue";
import DeleteDialog from "@/components/base/dialogs/DeleteDialog.vue";


const {
  store,
  headers,
  fields,
  editedItem,
  defaultItem,
  ifHyperlink,
  modelID,
  id,
} = defineProps([
  "store",
  "headers",
  "fields",
  "editedItem",
  "defaultItem",
  "ifHyperlink",
  "modelID",
  "id",
]);



const { collection, errors, res, loading } = storeToRefs(store);
const emit = defineEmits(["alert"]);

async function initialize() {
  if (modelID) {
    await store.fetchItems(modelID);
  } else if (id) {
    await store.fetchAssociated(id);
  } else {
    await store.fetchItems();
  }
}

const form = ref();
const dialog = ref(false);
const dialogDelete = ref(false);
const dialogDeactivate = ref(false);
const search = ref("");
const selected = ref([]);
const editedItemData = ref(editedItem);
const emittedImage = ref("");
const image = ref("");

const editedIndex = ref(-1);

const formTitle = computed(() => {
  return editedIndex.value === -1
    ? "Create a new " + store.name
    : "Edit " + store.name;
});

//gets the image from image component
const handleImageData = (data) => {
  editedItemData.value.image = data;
  emittedImage.value = data;
};

// identifies the form type and returns proper data set
// checks if the form has an image upload section
// if true then returns FormData object
// if false , returns normal object type
function formType() {
  if (editedItemData.value.image && emittedImage.value) {
    const formData = new FormData();
    for (const key in editedItemData.value) {
      formData.append(key, editedItemData.value[key]);
    }
    return formData;
  } else {
    return editedItemData.value;
  }
}

function editItem(item) {
  editedIndex.value = collection.value.indexOf(item);
  editedItemData.value = Object.assign({}, item);
  dialog.value = true;
  image.value = item.image;
}

function deleteItem(val) {
  if (selected.value.length) {
    dialogDelete.value = true;
  } else {
    emit("alert", "Select data(s) to delete first.", "error");
  }
}

const deleteItemConfirm = async () => {
  const arrayOfIds = selected.value.map((id) => {
    return {
      id,
    };
  });
  await store.deleteItemHandler(arrayOfIds);
  if (res.value) {
    collection.value = collection.value.filter(
      (value) => !selected.value.includes(value.id)
    );
    emit("alert", res.value.message, "success");
    selected.value = [];
  } else {
    emit("alert", errors.value, "error");
    console.log(errors.value);
  }
  res.value = null;
  closeDelete();
};

function close() {
  dialog.value = false;
  nextTick(() => {
    image.value = "";
    editedItemData.value = Object.assign({}, defaultItem);
    editedIndex.value = -1;
    errors.value = {};
  });
}

function closeDelete() {
  dialogDelete.value = false;
  dialogDeactivate.value = false;
  nextTick(() => {
    editedItemData.value = Object.assign({}, defaultItem);
    editedIndex.value = -1;
  });
}

const exportItems = async () => {
  await store.handleExport();

  const headers = [
    "ID",
    "Name",
    "Surname",
    "Country",
    "Phone",
    "Email",
    "status",
  ];

  const csvContent =
    "data:text/csv;charset=utf-8," +
    encodeURIComponent(headers.join(",") + "\n" + store.csvData);

  const downloadLink = document.createElement("a");
  downloadLink.setAttribute("href", csvContent);
  downloadLink.setAttribute("download", store.name + ".csv");
  document.body.appendChild(downloadLink);

  downloadLink.click();

  document.body.removeChild(downloadLink);
};

const save = async () => {
  editedItemData.value.brand_id = modelID;
  const { valid } = await form.value.validate();
  if (valid) {
    if (editedIndex.value > -1) {
      const data = formType();
      await store.updateItemHandler(editedItemData.value.id, data);

      if (res.value) {
        initialize()
        close();
        emit("alert", res.value.message, res.value.status);
      } else {
        emit("alert", errors.value, "error");
      }
    } else {
      const data = formType();
      await store.addItemHandler(data);

      if (res.value) {
        initialize()
        close();
        emit("alert", res.value.message, res.value.status);
      } else {
        emit("alert", errors.value, "error");
      }
    }
    res.value = null;
  }
};

watch(dialog, (val) => {
  val || close();
});

watch(dialogDelete, (val) => {
  val || closeDelete();
});

watch(dialogDeactivate, (val) => {
  val || closeDelete();
});

initialize();
</script>
