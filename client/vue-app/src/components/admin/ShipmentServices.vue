<template>
    <v-text-field v-show="xs || sm" class="mb-2" v-model="search" prepend-inner-icon="mdi-magnify" density="compact"
        label="Search" single-line flat hide-details variant="solo-filled">
    </v-text-field>

    <v-data-table v-model="selected" :headers="headers" :items="collection" :search="search" :loading="loading"
        :sort-by="[{ key: 'id', order: 'desc' }]" show-select :mobile="xs || sm">
        <template v-slot:item.id="{ item }">
            <span @click="redirect(item)"
                class="text-decoration-underline font-weight-bold text-blue-darken-4 cursor-pointer">
                {{ item.id }}
            </span>
        </template>
        <template v-slot:top>
            <v-toolbar color="#071d35" class="px-3" flat>
                <Button color="#66BB6A" @click="exportItems" variant="flat" class="mr-2" label="Export"
                    icon="mdi-file-download" />
                <Button color="#5C6BC0" variant="flat" label="Import" class="mr-2" icon="mdi-file-upload"
                    @click="showImport" />
                <v-spacer></v-spacer>
                <v-spacer></v-spacer>
                <Button color="#EF5350" variant="flat" label="Delete" class="mr-2" icon="mdi-trash-can-outline"
                    @click="deleteItem" />
                <v-dialog v-model="dialog" max-width="500px">
                    <template v-slot:activator="{ props }">
                        <Button color="#00ADB5" variant="flat" label="New" class="mr-2" icon="mdi-plus"
                            v-bind="props" />
                    </template>

                    <v-card>
                        <v-card-title class="d-flex justify-space-between align-center">
                            <span class="text-h6 text-uppercase">{{ formTitle }}</span>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-container>
                            <v-card-text>
                                <v-form ref="form">
                                    <v-text-field label="Title" v-model="editedItem.title"
                                        :rules="[$rules.required]"></v-text-field>
                                    <v-textarea label="Description" rows="2" v-model="editedItem.description"
                                        :rules="[$rules.required]"></v-textarea>
                                </v-form>


                            </v-card-text>
                            <v-card-actions class="mx-2">
                                <v-spacer></v-spacer>
                                <v-btn color="blue-darken-1" variant="outlined" @click="close">Cancel</v-btn>
                                <v-btn color="blue-darken-1" variant="elevated" :loading="loading"
                                    @click="save">Save</v-btn>
                            </v-card-actions>
                        </v-container>
                    </v-card>
                </v-dialog>

                <v-text-field v-show="!(xs || sm)" width="200px" v-model="search" prepend-inner-icon="mdi-magnify"
                    density="compact" label="Search" rounded single-line flat hide-details variant="solo-filled">
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

    <DeleteDialog v-model="dialogDelete" item="shipment service" type="delete" :loading="loading" @close="closeDelete"
        @confirm="deleteItemConfirm" />
</template>

<script setup>
import { useShipmentServiceStore } from '@/stores/shipment-services'
import { useSnackbarStore } from '@/stores/snackbar';
import { storeToRefs } from 'pinia';
import { ref, computed } from 'vue';
import { useRouter } from "vue-router";
import { useDisplay } from 'vuetify/lib/framework.mjs';
import Button from '../base/form-elements/Button.vue';
import DeleteDialog from '../base/dialogs/DeleteDialog.vue';


const router = useRouter()

const { xs, sm } = useDisplay()

const store = useShipmentServiceStore()

const { collection, res, errors, loading } = storeToRefs(store)

const search = ref("")
const editedIndex = ref(-1)
const form = ref()
const dialog = ref(false)
const selected = ref([])
const dialogDelete = ref(false)

const headers = [
    { title: "ID", key: "id", align: "start", width: "10%" },
    { title: "Title", key: "title" },
    { title: "Description", key: "description" },
    { title: "Actions", key: "actions", align: "end" },
]

const formTitle = computed(() => {
    return `${editedIndex.value === -1 ? "New Shipment Service" : "Edit Shipment Service"}`;
})


const editedItem = ref({
    title: "",
    description: ""
})

const defaultItem = ref({
    title: "",
    description: ""
})

async function initialize() {
    await store.fetchServices()
}

const redirect = (item) => {
    router.push({ name: 'Shipment Account', params: { id: item.id } })
}

function editItem(item) {
    editedIndex.value = collection.value.indexOf(item);
    editedItem.value = Object.assign({}, item);
    dialog.value = true;
}

const save = async () => {
    const { valid } = await form.value.validate();
    if (valid) {
        if (editedIndex.value > -1) {
            await store.handleUpdatedService(editedItem.value.id, editedItem.value);
            if (res.value) {
                initialize()
                snackbarShow(res.value.message, res.value.status);
                close()
            } else {
                snackbarShow(errors.value, "error");
            }
        } else {
            await store.handleAddedService(editedItem.value);
            if (res.value) {
                initialize()
                snackbarShow(res.value.message, res.value.status);
                close();
            } else {
                snackbarShow(errors.value, "error");
            }
        }
    }
    res.value = null;
}



function deleteItem() {
    if (selected.value.length) {
        dialogDelete.value = true;
    } else {
        snackbarShow("Select item(s) to delete first.", "error");
    }
}

const deleteItemConfirm = async () => {
    const ids = selected.value.map((id) => {
        return {
            id,
        };
    });

    await store.handleDeletedServices(ids);
    if (res.value) {
        initialize()
        snackbarShow(res.value.message, "success");
        selected.value = [];
        closeDelete();
    } else {
        snackbarShow(errors.value, "error");
    }
    res.value = null;
};

function close() {
    dialog.value = false;
    setTimeout(() => {
        editedItem.value = Object.assign({}, defaultItem.value);
        editedIndex.value = -1;
        errors.value = {};
    }, 150);
}

function closeDelete() {
    dialogDelete.value = false;
    editedItem.value = Object.assign({}, defaultItem.value);
}

const snackbarShow = (message, type) => {
    useSnackbarStore().showSnackbar(message, type);
};

initialize()

</script>