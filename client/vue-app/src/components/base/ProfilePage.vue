<style scoped>
.v-avatar {
  opacity: 0.6;
}

.v-avatar:not(.on-hover) {
  opacity: 1;
}
</style>

<template>
  <v-snackbar min-width="100" v-model="res" :color="color" :timeout="3000">
    <div class="text-center">{{ res.message }}</div>
  </v-snackbar>

  <v-container fluid>
    <v-row dense>
      <v-spacer></v-spacer>
      <v-col cols="10">
        <v-btn v-if="store.user.role !== 'admin'" @click="goBack" color="#00ADB5" variant="text"
          prepend-icon="mdi-chevron-left" class="mb-3">Main</v-btn>
        <v-card class="pa-6">
          <div v-if="store.user.role !== 'admin'" class="mb-5">
            <span class="text-h4 font-weight-medium text-grey-darken-2">Profile</span>
            <v-divider class="my-2"></v-divider>
          </div>
          <v-card-title class="text-center my-4">
            <v-hover v-slot="{ isHovering, props }">
              <v-avatar v-bind="props" class="border-thin" size="250">
                <v-img :src="imageUrl" cover>
                  <div v-if="isHovering" class="d-flex bg-grey-darken-3 opacity-70 justify-center align-end rounded-circle"
                    style="height: 250px; width: 250px;">
                    <v-icon class="cursor-pointer" color="white" size="40" @click="openFileInput">mdi-camera</v-icon>
                  </div>
                </v-img>
              </v-avatar>
              <input @change="handleFileUpload" type="file" accept="image/*" ref="fileInput" class="d-none" />
            </v-hover>

          </v-card-title>

          <v-card-text>
            <v-container>
              <v-row dense>
                <v-col cols="12" md="6"><v-text-field class="mx-4" color="#00ADB5" variant="underlined" readonly
                    v-model="userInfo.name" label="Name"></v-text-field></v-col>
                <v-col cols="12" md="6"><v-text-field class="mx-4" color="#00ADB5" variant="underlined" readonly
                    v-model="userInfo.phone" label="Phone Number"></v-text-field></v-col>
              </v-row>
              <v-row dense>
                <v-col cols="12" md="6"><v-text-field class="mx-4" color="#00ADB5" variant="underlined" readonly
                    v-model="userInfo.email" label="Email"></v-text-field></v-col>
                <v-col cols="12" md="6"><v-text-field class="mx-4" color="#00ADB5" variant="underlined" readonly
                    v-model="userInfo.country" label="Country"></v-text-field></v-col>
              </v-row>
              <v-row dense>
                <v-col cols="12" md="6"><v-text-field class="mx-4" color="#00ADB5" variant="underlined" readonly
                    v-model="userInfo.last_login" label="Last Login"></v-text-field></v-col>
              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions class="d-flex justify-end mt-5">
            <v-btn @click="save" class="mx-4" :loading="loading" variant="elevated" color="#00ADB5">Save</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
      <v-spacer></v-spacer>
    </v-row>
  </v-container>
</template>

<script setup>
import { storeToRefs } from "pinia";
import { useRoute, useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import api from "/src/http/axios-setup.js";
import { ref, onBeforeMount } from "vue";

const router = useRouter();
const res = ref(null); // Holds the response status and message

const loading = ref(false); // Indicates whether a loading spinner should be displayed in the save button

const color = ref(""); // Color for snackbar

const store = useAuthStore();

const { userInfo } = storeToRefs(store);

const imageUrl = ref(store.user.avatar);

const fileInput = ref(null);

const file = ref(null);

const openFileInput = () => {
  fileInput.value.click();
};

async function initialize() {
  await store.handleUserInformation();
}
initialize();

const handleFileUpload = (event) => {
  file.value = event.target.files[0];
  imageUrl.value = URL.createObjectURL(file.value);
};

const save = async () => {
  const formData = new FormData();
  formData.append("image", file.value);
  try {
    loading.value = true;
    const { data } = await api.post("/api/upload-image", formData);
    res.value = data;
    color.value = res.value.status ? "success" : "error";
    loading.value = false;
  } catch (error) {
    loading.value = false;
    color.value = "error";
  }
};

const goBack = () => {
  router.go(-1);
}
</script>
