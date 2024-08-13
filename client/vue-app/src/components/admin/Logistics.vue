<template>

  <LogisticFilter :panel="panel" />
  <v-card>
    <v-card-text class="ma-0 pa-0">
      <v-row no-gutters>
      <v-spacer></v-spacer>
      <v-col cols="12" lg="2" md="12" sm="12" class="d-flex justify-lg-end justify-start px-2">
        <v-btn variant="text" rounded="0" prepend-icon=" mdi-filter" color="#00ADB5" class="my-2" @click="showFilter"
            :active="!!(panel.length)">
            Filter
          </v-btn>
      </v-col>
    </v-row>

    <LogisticDatatable />
    </v-card-text>
  
  </v-card>
</template>

<script setup>
import { ref } from "vue";

import LogisticFilter from "@/components/base/filter-components/LogFilter.vue";
import LogisticDatatable from "./tables/LogisticDatatable.vue";
import { useSnackbarStore } from "@/stores/snackbar";

const tab = ref(null);
const panel = ref([]);


function showFilter() {
  if (panel.value.length) {
    panel.value = [];
  } else panel.value = ["filter"];
}

const snackbarShow = (message, type) => {
  useSnackbarStore().showSnackbar(message, type);
};

</script>
