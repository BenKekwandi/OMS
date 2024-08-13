<template>
  <v-toolbar :elevation="2" color="white" density="comfortable">
    <v-tabs v-model="tab" grow color="#00ADB5">
      <v-tab value="offers">OFFERS</v-tab>
      <v-tab value="orders">ORDERS</v-tab>
      <v-tab v-show="role === 'pm'" value="suppliers">SUPPLIERS</v-tab>
      <v-tab v-show="role === 'sm'" value="customers">CUSTOMERS</v-tab>
      <v-tab value="confirmations">CONFIRMATIONS</v-tab>
    </v-tabs>
  </v-toolbar>
  <v-container fluid>
    <v-card>
      <!-- <v-tabs v-model="tab" color="error" align-tabs="start">
      <v-tab class="bg-white" value="offers">OFFERS</v-tab>
      <v-tab class="bg-white" value="orders">ORDERS</v-tab>
      <v-tab class="bg-white" value="{{ `${rdStore.name.toLowerCase()}s` }}">{{
        `${rdStore.name.toUpperCase()}S`
        }}</v-tab>
      <v-tab class="bg-white" value="confirmations">CONFIRMATIONS</v-tab>
    </v-tabs> -->

      <!-- <v-window v-model="tab">
      <v-window-item value="offers">
        <Offers :snackbarShow="snackbarShow" :permission="role === 'pm'" />
      </v-window-item>

      <v-window-item value="orders">
        <Orders :snackbarShow="snackbarShow" :permission="role === 'sm'" />
      </v-window-item>

      <v-window-item v-if="role === 'pm'" value="{{ `${rdStore.name.toLowerCase()}s` }}">
        <Suppliers @alert="snackbarShow" :store="rdStore" />
      </v-window-item>
      <v-window-item v-else-if="role === 'sm'" value="{{ `${rdStore.name.toLowerCase()}s` }}">
        <Customers @alert="snackbarShow" :store="rdStore" />
      </v-window-item>

      <v-window-item value="confirmations">
        <Confirmations @alert="snackbarShow" :role="role" :store="confirmations" />
      </v-window-item>
    </v-window> -->

      <v-card-text>
        <v-tabs-window v-model="tab">
          <v-tabs-window-item value="offers">
            <div class="text-h6 text-capitalize ml-2">{{ tab }}</div>
            <Offers :permission="role === 'pm'" />
          </v-tabs-window-item>

          <v-tabs-window-item value="orders">
            <div class="text-h6 text-capitalize ml-2">{{ tab }}</div>
            <Orders :permission="role === 'sm'" />

          </v-tabs-window-item>

          <v-tabs-window-item v-show="role === 'pm'" value="suppliers">
            <div class="text-h6 text-capitalize ml-2">{{ tab }}</div>
            <Suppliers :store="rdStore" />
          </v-tabs-window-item>
          <v-tabs-window-item v-show="role === 'sm'" value="customers">
            <div class="text-h6 text-capitalize ml-2">{{ tab }}</div>
            <Customers :store="rdStore" />
          </v-tabs-window-item>
          <v-tabs-window-item value="confirmations">
            <div class="text-h6 text-capitalize ml-2">{{ tab }}</div>
            <Confirmations :role="role" :store="confirmations" ref="confirmationRef" />
          </v-tabs-window-item>
        </v-tabs-window>
      </v-card-text>

    </v-card>
  </v-container>

</template>

<script setup>
import { ref, watch } from "vue";
import Customers from "@/components/user/Customers.vue";
import Suppliers from "@/components/user/Suppliers.vue";
import Offers from "@/components/user/Offers.vue";
import Orders from "@/components/user/Orders.vue";
import Confirmations from "@/components/user/Confirmations.vue";
import { customerStore } from "@/stores/customer.js";
import { supplierStore } from "@/stores/supplier.js";
import { useAuthStore } from "@/stores/auth.js";
import { smConfirmationStore } from "@/stores/sm-confirmation.js";
import { pmConfirmationStore } from "@/stores/pm-confirmation.js";


const tab = ref(null);
const role = useAuthStore().user.role;
const rdStore = role === "pm" ? supplierStore() : customerStore();
const confirmations =
  role === "pm" ? pmConfirmationStore() : smConfirmationStore();

const confirmationRef = ref(null);

watch(() => tab.value, (val) => {
  if(val === 'confirmations' && confirmationRef.value) {
    console.log('yes');
    confirmationRef.value.initialize();
  }
});

</script>

<style scoped>
.v-tab.v-tab.v-btn {
  max-width: none !important;
}


.v-tabs,
.text-h6 {
  color: rgb(7, 29, 53) !important;
}
</style>
