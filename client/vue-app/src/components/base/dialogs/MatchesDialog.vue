<template>
  <v-dialog v-model="dialogMatches" max-width="1200px">
    <v-card>
      <v-container fluid>
        <v-card-title class="mx-2 d-flex">
          <div class="text-h5 text-uppercase">
            Propose to
            <span class="font-weight-bold">{{ order.customer.name }}</span>
          </div>
          <v-spacer></v-spacer>
          <span class="text-h5 text-uppercase text-end">{{ order.brand.name }} ({{ order.reference_number }})</span>
        </v-card-title>
        <v-divider class="my-2"></v-divider>
        <v-card-text>
          <v-data-table :headers="matchedOrderHeaders" :items="[order]" hide-default-footer density="compact">
            <template v-slot:item="{ item }">
              <tr style="background-color: #fafafa">
                <td>{{ item.id }}</td>
                <td>{{ item.created_at }}</td>
                <td>{{ item.other_featueres }}</td>
                <td>{{ item.deadline }}</td>
                <td class="text-end">
                  <img :src="item.image" :alt="item.alt" :width="80" :height="80" />
                </td>
              </tr>
            </template>
          </v-data-table>
          <br />
          <br />
          <div class="text-h5 text-uppercase">
            Matched Offers (<span class="font-weight-bold">
              {{ order.matches }}</span>)
          </div>
          <v-divider :thickness="2" color="#00ADB5" class="my-2 border-opacity-100"></v-divider>
          <v-data-table-virtual class="mb-6" :headers="matchedOfferHeaders" :items="matches" :loading="loading"
            @update:modelValue="(newValue) => updateSelectedMatch(newValue[0])" select-strategy="single" show-select
            density="compact" :height="matchesTableHeight">
            <template v-slot:item.image="{ item }">
              <img :src="item.image" :alt="item.alt" :width="80" :height="80" />
            </template>
          </v-data-table-virtual>

          <br />

          <div v-if="selectedMatch.id">
            <span class="text-h5 text-uppercase">Proposal Details</span>
            <v-divider :thickness="2" color="#00ADB5" class="my-2 border-opacity-100"></v-divider>
            <v-form ref="form">
              <v-row class="my-2">
                <v-col cols="12" sm="5">
                  <v-row>
                    <v-col cols="6" md="3">
                      <v-text-field variant="underlined" label="RRP" :rules="[rules.required]" readonly
                        v-model="selectedMatch.rrp_price" suffix="$"></v-text-field>
                    </v-col>
                    <v-col cols="6" md="3">
                      <v-text-field variant="underlined" label="Sell price" :rules="[rules.required]"
                        v-model.number="confirmationToSend.sell_price"
                        @update:modelValue="calculateProfit" suffix="$"></v-text-field>
                    </v-col>
                    <v-col cols="6" md="3">
                      <v-text-field variant="underlined" label="Delivery days" :rules="[
                        rules.required,
                        rules.delivery_days(
                          confirmationToSend.delivery_days,
                          order.deadline
                        ),
                      ]" v-model.number="confirmationToSend.delivery_days"></v-text-field>
                    </v-col>
                    <v-col cols="6" md="3">
                      <v-text-field variant="underlined" label="Profit" :rules="[rules.required]"
                        :model-value="confirmationToSend.profit" suffix="%"></v-text-field>
                    </v-col>
                  </v-row>
                </v-col>

                <v-spacer></v-spacer>
                <v-col cols="12" sm="5">
                  <v-text-field variant="underlined" label="Offer notes" prepend-inner-icon="mdi-note"
                    v-model="confirmationToSend.offer_notes"></v-text-field>
                </v-col>
              </v-row>
            </v-form>
          </div>
        </v-card-text>

        <v-card-actions class="mx-2">
          <v-spacer></v-spacer>
          <v-btn class="mx-2 my-2" color="primary" variant="outlined" @click="close">
            Close
          </v-btn>
          <v-btn v-if="selectedMatch.id" class="px-6 mx-2" color="primary" variant="elevated" :loading="propLoading"
            @click="createNewProposal">
            Submit
          </v-btn>
        </v-card-actions>
      </v-container>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { ref, computed, watch, nextTick } from "vue";
import { storeToRefs } from "pinia";
import { rules } from "/src/includes/customValidationRules.js";
import { matchesStore } from "@/stores/matches.js";

const dialogMatches = defineModel({ type: Boolean });

const store = matchesStore();

const props = defineProps({ order: Object, proposalStore: Object });

const { collection, loading } = storeToRefs(store);

const { loading: propLoading } = storeToRefs(props.proposalStore);

const emits = defineEmits("createProposal");

const selectedMatch = ref({});
const matchesTableHeight = ref(320);
const form = ref();

watch(
  () => props.order,
  async (val) => {
    if (val.id) {
      await store.fetchItems(val.id);
      confirmationToSend.value.order_id = val.id;
      matchesTableHeight.value = 320;
    }
  }
);

const matchedOrderHeaders = ref([
  { title: "ID", key: "id" },
  { title: "Created", key: "created_at" },
  { title: "Other features", key: "other_features" },
  { title: "Deadline", key: "deadline" },
  { title: "Image", key: "image", align: "end" },
]);

const matchedOfferHeaders = ref([
  { title: "ID", key: "id" },
  { title: "Created", key: "created_at" },
  { title: "Supplier", key: "supplier.name" },
  { title: "Discount", key: "discount" },
  { title: "Other features", key: "other_features" },
  { title: "Net price", key: "net_price" },
  { title: "RRP price", key: "rrp_price" },
  { title: "Availability", key: "availability" },
  { title: "Image", key: "image", align: "end" },
]);

const confirmationToSend = ref({
  offer_id: "",
  order_id: "",
  sell_price: "",
  delivery_days: "",
  profit: "",
  offer_notes: "",
});

const defaultConfirmationToSend = ref({
  offer_id: "",
  order_id: "",
  sell_price: "",
  delivery_days: "",
  profit: "",
  offer_notes: "",
});

function updateSelectedMatch(searchId) {
  if (searchId) {
    selectedMatch.value = matches.value.find((match) => match.id === searchId);
    confirmationToSend.value.offer_id = parseInt(selectedMatch.value.id);
    matchesTableHeight.value = 130;
  } else {
    selectedMatch.value = {};
    matchesTableHeight.value = 320;
    for (let key in confirmationToSend.value) {
      if (key !== "order_id") {
        confirmationToSend.value[key] = "";
      }
    }
  }
}

const calculateProfit = () => {
  confirmationToSend.value.profit = confirmationToSend.value.sell_price
    ? parseFloat(
      (
        ((confirmationToSend.value.sell_price -
          selectedMatch.value.net_price) /
          selectedMatch.value.net_price) *
        100
      ).toFixed(2)
    )
    : 0;
};

const matches = computed(() => {
  return Object.keys(selectedMatch.value).length === 0
    ? collection.value
    : collection.value.filter(
      (collItem) => collItem.id === selectedMatch.value.id
    );
});

function close() {
  dialogMatches.value = false;
  nextTick(() => {
    confirmationToSend.value = Object.assign(
      {},
      defaultConfirmationToSend.value
    );
    matchesTableHeight.value = 320;
    selectedMatch.value = {};
    collection.value = [];
  });
}

async function createNewProposal() {
  const { valid } = await form.value.validate();
  if (valid) {
    emits("createProposal", confirmationToSend.value);
  }
}

watch(dialogMatches, (val) => {
  val || close();
});
</script>
