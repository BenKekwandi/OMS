<template>
  <v-card>
    <v-tabs v-model="tab" color="#00ADB5" align-tabs="start">
      <v-tab class="tab" value="pm">Purchase Manager</v-tab>
      <v-tab class="tab" value="sm">Sales Manager</v-tab>
      <v-tab class="tab" value="acm">Accounting Manager</v-tab>
      <v-tab class="tab" value="lgm">Logistic Manager</v-tab>
    </v-tabs>
    <v-divider class="mb-2"></v-divider>
    <v-window v-model="tab">
      <v-window-item value="pm">
        <UsersDatatable
          @alert="snackbarShow"
          :data="collection.filter((item) => item.role === 'pm')"
          :countries="country_store.collection"
          :errors="errors"
          @upload="handleUpload"
          @update="handleUpdate"
          @deactivate="handleDeactivate"
          @export="handleExport"
          :loading="loading"
          :res="res"
          :role="'pm'"
          :store="user_store" 
        />
      </v-window-item>

      <v-window-item value="sm">
        <UsersDatatable
          @alert="snackbarShow"
          :data="collection.filter((item) => item.role === 'sm')"
          :countries="country_store.collection"
          :errors="errors"
          @upload="handleUpload"
          @update="handleUpdate"
          @deactivate="handleDeactivate"
          @export="handleExport"
          :loading="loading"
          :res="res"
          :role="'sm'"
          :store="user_store" 
        />
      </v-window-item>

      <v-window-item value="acm">
        <UsersDatatable
          @alert="snackbarShow"
          :data="collection.filter((item) => item.role === 'logistic')"
          :countries="country_store.collection"
          @upload="handleUpload"
          @update="handleUpdate"
          @deactivate="handleDeactivate"
          @export="handleExport"
          :errors="errors"
          :loading="loading"
          :res="res"
          :role="'accounting'"
          :store="user_store" 
        />
      </v-window-item>

      <v-window-item value="lgm">
        <UsersDatatable
          @alert="snackbarShow"
          :data="collection.filter((item) => item.role === 'accounting')"
          :countries="country_store.collection"
          @upload="handleUpload"
          @update="handleUpdate"
          @deactivate="handleDeactivate"
          @export="handleExport"
          :errors="errors"
          :loading="loading"
          :res="res"
          :role="'logistic'"
          :store="user_store" 
        />
      </v-window-item>
    </v-window>
  </v-card>
</template>

<script setup>
import { ref } from "vue";
import { storeToRefs } from "pinia";
import { useUserStore } from "@/stores/users";
import { countryStore } from "@/stores/countries";
import { useSnackbarStore } from "@/stores/snackbar";
import UsersDatatable from "../admin/tables/UsersDatatable.vue";

const tab = ref(null);

const user_store = useUserStore();
const country_store = countryStore();
const { collection, loading, errors, res } = storeToRefs(user_store);

async function initialize() {
  user_store.fetchUsers();
  country_store.fetchItems();
}

initialize();

async function handleUpload(newUser) {
  await user_store.handleAddedUser(newUser);
  if (res.value) {
    snackbarShow(res.value.message, res.value.status);
    initialize();
  } else {
    snackbarShow(errors.value, "error");
  }
}

async function handleDeactivate(ids) {
  const arrayOfIds = ids.map((id) => {
    return { id };
  });
  await user_store.handleDeactivatedUsers(arrayOfIds);
  if (res.value) {
    snackbarShow(res.value.message, res.value.status);
    initialize();
  } else {
    snackbarShow(errors.value, "error");
  }
}

async function handleUpdate(id, user) {
  await user_store.handleUpdatedUser(id, user);
  if (res.value) {
    snackbarShow(res.value.message, res.value.status);
    initialize();
  } else {
    snackbarShow(errors.value, "error");
  }
}


const snackbarShow = (message, type) => {
  useSnackbarStore().showSnackbar(message, type);
};
</script>
