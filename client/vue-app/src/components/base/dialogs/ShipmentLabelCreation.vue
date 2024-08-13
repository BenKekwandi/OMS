<template>
  <v-dialog v-model="dialog" max-width="1000" transition="dialog-top-transition">
    <v-card>
      <v-card-title class="d-flex justify-space-between align-center mb-0 pb-0">
        <div class="text-h6 text-uppercase">
          Label Management
          <br v-if="$vuetify.display.sm || $vuetify.display.xs" />
          For manual shipment
          <span class="font-weight-bold"><br v-if="$vuetify.display.xs" />#139</span>
        </div>

        <v-btn icon="mdi-close" size="small" variant="text" @click="dialog = false"></v-btn>
      </v-card-title>
      <v-divider class="mb-4"></v-divider>
      <v-container>
        <v-card-text>
          <div class="text-uppercase text-body-1">Current Label</div>
          <v-divider class="my-2" color="primary" opacity="100"></v-divider>

          <v-row dense class="mt-1">
            <v-col cols="12" md="6">
              <v-row dense>
                <v-col class="d-flex align-end justify-md-end justify-start">
                  <div class="text-body-2 text-high-emphasis mr-6">
                    Shipping Service
                  </div>
                </v-col>
                <v-col class="d-flex align-end justify-md-end justify-start"><v-responsive max-width="200"
                    min-width="200"><v-text-field density="compact" variant="underlined"
                      hide-details></v-text-field></v-responsive>
                </v-col>
              </v-row>
            </v-col>
            <v-col cols="12" md="6">
              <v-row dense>
                <v-col class="d-flex align-end justify-md-end justify-start">
                  <div class="text-body-2 text-high-emphasis mr-6">
                    Expected Collection At
                  </div>
                </v-col>
                <v-col class="d-flex align-end justify-md-end justify-start">
                  <v-responsive max-width="200" min-width="200">
                    <DatePicker color="#00ADB5" density="compact" hide_details clearable />
                  </v-responsive>
                </v-col>
              </v-row>
            </v-col>
          </v-row>

          <v-row dense class="mt-1">
            <v-col cols="12" md="6">
              <v-row dense>
                <v-col class="d-flex align-end justify-md-end justify-start">
                  <div class="text-body-2 text-high-emphasis mr-6">
                    Shipping Account
                  </div>
                </v-col>
                <v-col class="d-flex align-end justify-md-end justify-start"><v-responsive max-width="200"
                    min-width="200"><v-text-field density="compact" variant="underlined"
                      hide-details></v-text-field></v-responsive>
                </v-col>
              </v-row>
            </v-col>
            <v-col cols="12" md="6">
              <v-row dense>
                <v-col class="d-flex align-end justify-md-end justify-start">
                  <div class="text-body-2 text-high-emphasis mr-6">
                    Expected Delivery At
                  </div>
                </v-col>
                <v-col class="d-flex align-end justify-md-end justify-start">
                  <v-responsive max-width="200" min-width="200">
                    <DatePicker color="#00ADB5" density="compact" clearable hide_details />
                  </v-responsive>
                </v-col>
              </v-row>
            </v-col>
          </v-row>

          <v-row dense class="mt-1">
            <v-col cols="12" md="6">
              <v-row dense>
                <v-col class="d-flex align-end justify-md-end justify-start">
                  <div class="text-body-2 text-high-emphasis mr-6">Amount</div>
                </v-col>
                <v-col class="d-flex align-end justify-md-end justify-start"><v-responsive max-width="200"
                    min-width="200"><v-text-field density="compact" variant="underlined"
                      hide-details></v-text-field></v-responsive>
                </v-col>
              </v-row>
            </v-col>
            <v-col cols="12" md="6">
              <v-row dense>
                <v-col class="d-flex align-end justify-md-end justify-start">
                  <div class="text-body-2 text-high-emphasis mr-6">
                    Upload label
                  </div>
                </v-col>
                <v-col>
                  <v-responsive max-width="200" min-width="200">
                    <v-file-input @change="handleFileData" density="compact" variant="underlined"
                      hide-details></v-file-input>
                  </v-responsive>
                </v-col>
              </v-row>
            </v-col>
          </v-row>

          <v-row dense class="mt-1">
            <v-col cols="12" md="6">
              <v-row dense>
                <v-col class="d-flex align-end justify-md-end justify-start">
                  <div class="text-body-2 text-high-emphasis mr-6">
                    Tracking Number
                  </div>
                </v-col>
                <v-col class="d-flex align-end justify-md-end justify-start"><v-responsive max-width="200"
                    min-width="200"><v-text-field density="compact" variant="underlined"
                      hide-details></v-text-field></v-responsive>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
          <v-card-title class="d-flex justify-space-between align-center mb-0 pb-1 my-5">
            <span class="text-subtitle-1">INVOICE DETAILS</span>

            <v-btn :text="active ? 'HIDE' : 'SHOW'" :active="active"
              :prepend-icon="active ? 'mdi-chevron-up' : 'mdi-chevron-down'" variant="text"
              @click="showAddressDetails"></v-btn>
          </v-card-title>
          <v-divider color="#00ADB5" opacity="100" class="mx-4"></v-divider>

          <v-card-text>
            <v-expansion-panels v-model="panel" flat>
              <v-expansion-panel value="invoice">
                <v-expansion-panel-text>
                  <v-row dense>
                    <v-spacer></v-spacer>
                    <v-col cols="6" sm="3" md="3" class="pr-4">
                      <v-text-field density="comfortable" label="Invoice Date" variant="underlined"
                        readonly></v-text-field>
                    </v-col>
                    <v-col cols="6" sm="3" md="3" class="pr-4">
                      <v-text-field density="comfortable" label="Serial Number" variant="underlined"
                        readonly></v-text-field>
                    </v-col>
                    <v-col cols="6" sm="3" md="3" class="pr-4">
                      <v-text-field density="comfortable" label="Type" variant="underlined" readonly></v-text-field>
                    </v-col>
                    <v-col cols="6" sm="3" md="3" class="pr-4">
                      <v-text-field density="comfortable" label="Copies" variant="underlined" readonly></v-text-field>
                    </v-col>
                    <v-spacer></v-spacer>
                  </v-row>
                </v-expansion-panel-text>
              </v-expansion-panel>
            </v-expansion-panels>
          </v-card-text>
        </v-card-text>
        <div class="my-2 mx-1 d-flex justify-end">
          <v-btn class="text-none" color="#00ADB5" text="Create" @click="dialog = false"></v-btn>
        </div>
      </v-container>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { ref, onMounted, computed, watch } from "vue";
import DatePicker from "../form-elements/DatePicker.vue";

const dialog = defineModel({ type: Boolean });
const active = ref(false);
const panel = ref([""]);

function showAddressDetails() {
  active.value = !active.value;
  active.value ? (panel.value = ["invoice"]) : (panel.value = [""]);
}
</script>

<style scoped></style>
