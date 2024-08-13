<template>

    <v-data-table v-model="selected" :headers="headers" :items="collection" :search="search" :loading="loading"
        items-per-page="5" show-select :sort-by="[{ key: 'deactivated_at', order: 'desc' }]">
        <template v-slot:item.active="{ item }">
            <v-chip size="small" variant="outlined" label color="red-darken-3">
                {{ item.active }}
            </v-chip>
        </template>
        <template v-slot:top>
            <v-toolbar color="#071d35" class="px-3" flat>
                <v-spacer></v-spacer>
                <v-spacer></v-spacer>

                <v-btn class="mx-3" prepend-icon=" mdi-account-sync" @click="reactivateItem" color="#039BE5"
                    variant="flat">Reactivate</v-btn>
                <v-text-field v-model="search" prepend-inner-icon="mdi-magnify" density="compact" label="Search" rounded
                    single-line flat hide-details variant="solo-filled">
                </v-text-field>
            </v-toolbar>
        </template>


        <template v-slot:no-data>
            <v-btn color="primary" @click="initialize"> Reset </v-btn>
        </template>
    </v-data-table>
</template>

<script setup>
import { ref } from 'vue'
import { deactiveUserStore } from '@/stores/deactive-users';
import { storeToRefs } from 'pinia';
import { useSnackbarStore } from '@/stores/snackbar';

const store = deactiveUserStore()

const { collection, errors, loading, res } = storeToRefs(store)


const selected = ref([])
const search = ref("");

async function initialize() {
    await store.fetchItems()
}

const headers = ref([
    {
        title: 'Name',
        align: 'start',
        key: 'name',
    },
    { title: 'Surname', key: 'surname' },
    { title: 'Country', key: 'country' },
    { title: 'Role', key: 'role_name' },
    { title: 'Phone', key: 'phone' },
    { title: 'Email', key: 'email' },
    { title: 'Deactivated At', key: 'deactivated_at' },
    { title: 'Status', key: 'active' },
])


const reactivateItem = async () => {
    if (selected.value.length) {
        const arrayOfIds = selected.value.map((id) => {
            return {
                id,
            }
        })
        await store.handleReactivatedUsers(arrayOfIds)
        if (res.value) {
            collection.value = collection.value.filter((value) => !selected.value.includes(value.id));
            snackbarShow(res.value.message, 'success')
            selected.value = []
        } else {
            snackbarShow(errors.value, 'error')
        }
        res.value = null
    } else {
        snackbarShow('Select user(s) to reactivate first.', 'error')
    }
}

const snackbarShow = (message, type) => {
    useSnackbarStore().showSnackbar(message, type)
};



initialize()

</script>