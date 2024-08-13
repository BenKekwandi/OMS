<template>
  <v-layout class=" rounded rounded-lg">
    <Sidebar :toggle="toggleEvent" v-model="navDrawer" />
    <Header @toggle="toggleSidebar" />
    <v-main>
      <v-container fluid>

        <div class=" pt-4 pb-2">
          <v-breadcrumbs-item color="#737272" class="text-h5 font-weight-medium" :title="routeName">

          </v-breadcrumbs-item>
          <!-- <v-divider :thickness="2" class="mx-3 w-5" ></v-divider> -->
          <v-breadcrumbs color="#737272" class="pa-0" :items="items">
            <template v-slot:divider>
              <v-icon color="#737272" icon="mdi-chevron-right"></v-icon>
            </template>
          </v-breadcrumbs>
        </div>
        <v-divider></v-divider>
        <br />
        <router-view></router-view>
      </v-container>
    </v-main>
    <Footer />
  </v-layout>
</template>


<script setup>
import { ref, computed, watch } from "vue";
import Header from "@/components/layout/Header.vue"
import Sidebar from "@/components/layout/Sidebar.vue";
import Footer from "@/components/layout/Footer.vue";
import { useRouter } from "vue-router"
import { useDisplay } from "vuetify"
const { xs } = useDisplay()

const route = useRouter()

const items = computed(() => {
  if (typeof route.currentRoute.value.meta.breadcrumb === "function") {
    return route.currentRoute.value.meta.breadcrumb.call(this, route.currentRoute.value);
  }
  return route.currentRoute.value.meta.breadcrumb;
});

const routeName = computed(() => route.currentRoute.value.name)


const toggleEvent = ref(false)
const navDrawer = ref(true)

const toggleSidebar = () => {
  if(!xs.value) {
    toggleEvent.value = !toggleEvent.value
  } else {
    navDrawer.value = !navDrawer.value
  }
}



</script>

<style scoped>
.v-breadcrumbs-divider {
  padding: 0 !important;
}
</style>