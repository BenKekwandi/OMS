<template>
  <v-app-bar :color="color">
    <template v-if="store.user.role === 'admin'" v-slot:prepend>
      <v-app-bar-nav-icon @click="$emit('toggle')"></v-app-bar-nav-icon>
    </template>

    <v-list-item
      v-if="store.user.role !== 'admin'"
      prepend-avatar="/logo-small-HQNnTX16.svg"
    >
      <v-list-item-title>ORDER MANAGEMENT SYSTEM</v-list-item-title>
    </v-list-item>

    <v-spacer></v-spacer>

    <v-icon v-show="!ifMobile" class="mr-4"> mdi-bell-badge-outline</v-icon>
    <v-btn v-show="!ifMobile" @click="profile" icon>
      <v-tooltip activator="parent" location="bottom">Profile Page</v-tooltip>
      <v-avatar size="large">
        <v-img alt="Avatar" :src="store.user.avatar"></v-img
      ></v-avatar>
    </v-btn>
    <v-list-item v-show="!ifMobile">
      <v-list-item-title>{{ store.user.email }}</v-list-item-title>
      <v-list-item-subtitle>{{ store.user.role }}</v-list-item-subtitle>
    </v-list-item>
    <v-btn
      v-show="!ifMobile"
      icon="mdi-logout"
      class="mr-6"
      @click="logout"
    ></v-btn>

    <v-menu v-if="ifMobile">
      <template v-slot:activator="{ props }">
        <v-btn icon="mdi-dots-vertical" v-bind="props"></v-btn>
      </template>
      <v-card>
        <v-list>
          <v-list-item
            :prepend-avatar="store.user.avatar"
            :subtitle="store.user.role"
            :title="store.user.email"
          >
          </v-list-item>
        </v-list>
        <v-divider></v-divider>
        <v-list>
          <v-list-item title="Profile Page" @click="profile"></v-list-item>
          <v-list-item title="Notifications" @click="profile"></v-list-item>
          <v-list-item title="Logout" @click="logout"></v-list-item>
        </v-list>
      </v-card>
    </v-menu>
  </v-app-bar>
</template>

<script setup>
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import { ref, computed } from "vue";
import { useDisplay } from "vuetify";
const { mobile } = useDisplay();

const ifMobile = computed(() => mobile.value);

const store = useAuthStore();
const router = useRouter();

const color = store.user.role == "admin" ? "#F9F7F7" : "#071D35";

const logout = async () => {
  await store.handleLogout();
  router.push({ name: "login" });
};

const profile = async () => {
  if (store.user.role === "admin") router.push({ name: "Profile" });
  else {
    router.push({ name: "UserProfile" });
  }
};
</script>
