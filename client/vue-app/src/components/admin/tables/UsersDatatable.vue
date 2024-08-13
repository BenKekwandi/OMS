<template>
    <v-text-field v-show="xs" class="mb-2 mx-2" v-model="search" prepend-inner-icon="mdi-magnify" density="compact"
        label="Search" single-line flat hide-details variant="solo-filled">
    </v-text-field>
    <v-data-table v-model="selected" :search="search" show-select :loading="loading" :headers="headers"
        :items="props.data" :sort-by="[{ key: 'created_at', order: 'desc' }]" :mobile="sm || xs">
        <template v-slot:top>
            <v-toolbar color="#071d35" class="px-3" flat>
                <!-- <Button color="#66BB6A" @click="exportItems" variant="flat" class="mr-2" label="Export"
                    icon="mdi-file-download" /> -->
                <ExportButton :store="store" :role="role" :headers="exportHeaders" variant="flat" class="mr-2"/>
                <v-spacer></v-spacer>
                <v-spacer></v-spacer>

                <Button color="#EF5350" variant="flat" label="Deactivate" class="mr-2" icon="mdi-account-cancel-outline"
                    @click="deactivateItem" />

                <v-dialog v-model="dialog" max-width="600px">
                    <template v-slot:activator="{ props }">
                        <Button color="#00ADB5" variant="flat" label="New" class="mr-2" icon="mdi-plus"
                            v-bind="props" />
                    </template>

                    <v-card>
                        <v-container>
                            <v-card-title>
                                <span class="text-h5">{{ formTitle }}</span>
                            </v-card-title>
                            <v-divider class="mb-4 mx-4"></v-divider>

                            <v-card-text>
                                <v-form ref="form">
                                    <v-row>
                                        <v-col cols="12" sm="6">
                                            <v-text-field v-model="editedItem.name"
                                                prepend-inner-icon="mdi-account-circle" label="Name"
                                                :rules="[rules.required]"></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="6">
                                            <v-text-field v-model="editedItem.surname"
                                                prepend-inner-icon="mdi-account-circle" label="Surname"
                                                :rules="[rules.required]"></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" sm="6">
                                            <v-text-field v-model="editedItem.email" prepend-inner-icon="mdi-at"
                                                label="Email" :rules="[rules.required, rules.email]"></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="6">
                                            <v-text-field v-model="editedItem.phone" prepend-inner-icon="mdi-phone"
                                                label="Phone" :rules="[rules.phone]"></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-autocomplete v-model="editedItem.country"
                                                prepend-inner-icon="mdi-map-marker" :items="countries" item-value="name"
                                                item-title="name" label="Country"
                                                :rules="[rules.required]"></v-autocomplete>
                                        </v-col>
                                    </v-row>

                                    <v-row>
                                        <v-spacer></v-spacer>
                                        <v-col cols="6" class="d-flex justify-end">
                                            <v-btn color="primary" variant="outlined" class="mr-2" @click="close">
                                                Cancel
                                            </v-btn>
                                            <v-btn color="primary" :loading="props.loading" variant="flat"
                                                @click="save">
                                                Save
                                            </v-btn>
                                        </v-col>
                                    </v-row>
                                </v-form>
                            </v-card-text>



                        </v-container>
                    </v-card>
                </v-dialog>

                <v-text-field v-show="!xs" v-model="search" prepend-inner-icon="mdi-magnify" density="compact"
                    label="Search" rounded single-line flat hide-details variant="solo-filled">
                </v-text-field>
            </v-toolbar>
        </template>

        <template v-slot:item.status="{ item }">
            <v-chip size="small" variant="outlined" label color="light-blue-darken-2">
                {{ item.status }}
            </v-chip>
        </template>

        <template v-slot:item.actions="{ item }">

            <v-btn variant="text" size="small" @click="editItem(item)" color="#193a63" icon="mdi-pencil"></v-btn>
            <v-tooltip open-delay="500" v-if="item.role === 'pm'" text="Suppliers" location="bottom">
                <template v-slot:activator="{ props }">
                    <v-btn v-bind="props" :to="{ name: 'Suppliers', params: { id: item.id, name: item.name } }"
                        icon="mdi-account-file-text" variant="text" color="#193a63"></v-btn>
                </template>
            </v-tooltip>
            <v-tooltip open-delay="500" v-if="item.role == 'sm'" text="Customers" location="bottom">
                <template v-slot:activator="{ props }">
                    <v-btn v-bind="props" :to="{ name: 'Customers', params: { id: item.id, name: item.name } }"
                        icon="mdi-account-file-text" variant="text" color="#193a63"></v-btn>
                </template>
            </v-tooltip>
        </template>
    </v-data-table>

    <DeleteDialog v-model="dialogDeactivate" :loading="props.loading" item="manager" type="deactivate"
        @close="closeDeactivate" @confirm="deactivateItemConfirm" />
</template>

<script setup>
import { ref, defineProps, computed, watch, nextTick } from 'vue'
import { rules } from '@/includes/customValidationRules'
import { useDisplay } from 'vuetify'
import Button from "../../base/form-elements/Button.vue"
import DeleteDialog from "../../base/dialogs/DeleteDialog.vue"
import ExportButton from '@/components/base/form-elements/ExportButton.vue'


const { xs, sm } = useDisplay()

const props = defineProps({ data: Array, loading: Boolean, errors: Object, countries: Array, res: Object, role: String, store: Object })

const emit = defineEmits(["upload", "update", "deactivate", "alert"])

const form = ref()
const selected = ref([])
const search = ref('')
const dialog = ref(false)
const dialogDeactivate = ref(false)
const editedIndex = ref(-1)
const headers = ref([
    {
        title: "Name",
        align: "start",
        sortable: false,
        key: "name",
    },
    { title: "Surname", key: "surname" },
    { title: "Email", key: "email" },
    { title: "Phone", key: "phone" },
    { title: "Country", key: "country" },
    { title: "Creation Date", key: "created_at" },
    { title: "Status", key: "status", align: "center" },
    { title: "Actions", key: "actions", align: "end", sortable: false },
]);

const exportHeaders = [
    "ID",
    "Name",
    "Surname",
    "Country",
    "Phone",
    "Email",
    "status",
  ];



const editedItem = ref({
    name: "",
    surname: "",
    email: "",
    phone: "",
    country: "",
    role: props.role
});

const defaultItem = ref({
    name: "",
    surname: "",
    email: "",
    phone: "",
    country: "",
    role: props.role
});

function editItem(item) {
    editedIndex.value = props.data.indexOf(item);
    editedItem.value = Object.assign({}, item);
    dialog.value = true;
}


const formTitle = computed(() => {
    return editedIndex.value === -1 ? "Create a New Manager" : "Edit Manager"
})

async function save() {
    const { valid } = await form.value.validate()
    if (valid) {
        if (editedIndex.value > -1) {
            emit('update', editedItem.value.id, editedItem.value)


        } else {
            emit('upload', editedItem.value)

        }
    }
}


function deactivateItem() {
    if (selected.value.length) {
        dialogDeactivate.value = true;
    } else {
        emit("alert", "Select data(s) to deactivate first.", "error");
    }
}

function deactivateItemConfirm() {
    emit('deactivate', selected.value)
}

function close() {
    dialog.value = false;
    nextTick(() => {
        editedItem.value = Object.assign({}, defaultItem.value);
        editedIndex.value = -1;
    });
}

function closeDeactivate() {
    dialogDeactivate.value = false;
}

watch(dialog, (val) => {
    val || close()
})

watch(dialogDeactivate, (val) => {
    val || closeDeactivate()
})

watch(() => props.res, (val) => {
    if (val) {
        close()
        closeDeactivate()
        selected.value = []
    }
})
</script>