<template>
  <v-snackbar min-width="100" v-model="snackbar.show" :color="snackbar.color" :timeout="3000">
    <div class="d-flex justify-center align-center">
      <div>{{ snackbar.text }}</div>
    </div>
  </v-snackbar>
  <v-data-table :headers="headers" :loading="loading" :items="collection" :items-per-page="25" :search="search"
    :sort-by="[{ key: 'login_datetime', order: 'desc' }]">
    <template v-slot:top>
      <v-toolbar color="#071d35" class="px-3" flat>
        <v-spacer></v-spacer>
        <v-spacer></v-spacer>
        <v-text-field v-model="search" prepend-inner-icon="mdi-magnify" density="compact" label="Search" rounded
          single-line flat hide-details variant="solo-filled">
        </v-text-field>
      </v-toolbar>
    </template>



    <template v-slot:item.success="{ item }">
      <v-icon v-if="item.success" color="success">mdi-check-bold</v-icon>
      <v-icon v-else color="red-darken-1">mdi-close-thick</v-icon>
    </template>

    <!-- <template v-slot:item.status="{ item }">
      <v-icon v-if="item.status" color="success">mdi-check-bold</v-icon>
      <v-icon v-else color="red-darken-1">mdi-close-thick</v-icon>
    </template> -->

    <template v-slot:item.actions="{ item }">

      <v-btn color="#193a63" v-if="item.status" @click="block(item.user_id, item.ip_address)"
        variant="text">Block</v-btn>
      <v-btn color="#193a63" v-else @click="unblock(item.user_id, item.ip_address)" variant="text">Unblock</v-btn>

    </template>

  </v-data-table>
</template>

<script setup>
import { useSecurityStore } from "@/stores/security";
import { storeToRefs } from "pinia";
import { ref } from "vue";

const store = useSecurityStore();

const { collection, loading, res, errors } = storeToRefs(store);
const search = ref("");

async function initialize() {
  await store.fetchItems();
}

const headers = ref([
  {
    title: "User ID",
    align: "start",
    key: "user_id",
  },
  { title: "User", key: "identifier" },
  { title: "Login Datetime", key: "login_datetime" },
  { title: "IP Address", key: "ip_address" },
  { title: "Country", key: "country" },
  { title: "Region", key: "region" },
  // { title: "User Agent", key: "user_agent" },
  { title: "Status", key: "success" },
  { title: "Actions", key: "actions", sortable: false, align: "end" },
]);

const block = async (id, ip) => {
  await store.handleBlocked(id, ip)
  if (res.value) {
    snackbarShow(res.value.message, 'success')
    initialize()
  } else {
    snackbarShow(errors.value, 'error')
  }
  res.value = null
}

const unblock = async (id, ip) => {
  await store.handleUnblocked(id, ip)
  if (res.value) {
    snackbarShow(res.value.message, 'success')
    initialize()
  } else {
    snackbarShow(errors.value, 'error')
  }
  res.value = null
}

const snackbar = ref({
  show: false,
  text: "",
  color: "",
});

const snackbarShow = (message, type) => {
  snackbar.value = {
    show: true,
    text: message,
    color: type,
  };
};

initialize();
</script>
