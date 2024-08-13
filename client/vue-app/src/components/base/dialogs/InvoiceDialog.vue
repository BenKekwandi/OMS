<template>
  <v-dialog v-model="dialogInvoice" max-width="600px">
    <v-card>
      <v-container>
        <v-card-title>
          <span class="text-h5">Add an invoice</span>
        </v-card-title>
        <v-divider class="my-4 mx-4"></v-divider>
        <v-card-text>
          <v-row> </v-row>
          <v-row>
            <v-col cols="12" sm="6">
              <DatePicker v-model="editedInvoiceItem.invoicing_date" label="Invoice date" :rules="[$rules.required]"
                variant="default" />
            </v-col>
            <v-col cols="12" sm="6">
              <v-text-field label="Invoice number" v-model="editedInvoiceItem.invoice_number"
                :rules="[$rules.required]"></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" sm="6">
              <v-select label="Invoice company" v-model="editedInvoiceItem.invoice_company_id" item-title="company"
                item-value="id" :items="companyStorage.collection" :rules="[$rules.required]"></v-select>
            </v-col>
            <v-col cols="12" sm="6">
              <DatePicker v-model="editedInvoiceItem.payment_deadline" label="Payment Deadline"
                :rules="[$rules.required]" variant="default" />
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" sm="6">
              <v-text-field label="Amount" :rules="[
                $rules.required,
                $rules.invoice_amount(
                  editedInvoiceItem.amount,
                  order.proposal.sell_price
                ),
              ]" v-model.number="editedInvoiceItem.amount"></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <v-file-input label="Invoice file" @change="handleFileData"></v-file-input>
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <v-checkbox label="Real Invoice" v-model="editedInvoiceItem.is_real"></v-checkbox>
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-actions class="mx-2 my-4">
          <v-spacer></v-spacer>
          <v-btn color="primary" variant="outlined" @click="close">
            Cancel
          </v-btn>
          <v-btn class="px-4" color="primary" variant="elevated" :loading="props.loading" @click="upload">
            Upload
          </v-btn>
        </v-card-actions>
      </v-container>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { ref, onMounted } from "vue";
import DatePicker from "../form-elements/DatePicker.vue";
import { useInvoiceCompanyStore } from "@/stores/invoice-companies";

const dialogInvoice = defineModel({ type: Boolean });

const props = defineProps({ order: Object, loading: Boolean });

const emits = defineEmits(["upload"]);

const companyStorage = useInvoiceCompanyStore();

onMounted(async () => {
  await companyStorage.fetchItems();
});

const editedInvoiceItem = ref({
  invoicing_date: "",
  invoice_number: "",
  invoice_company_id: "",
  payment_deadline: "",
  amount: 0,
  file: null,
  is_real: false,
});

const defaultInvoiceItem = ref({
  invoicing_date: "",
  invoice_number: "",
  invoice_company_id: "",
  payment_deadline: "",
  amount: 0,
  file: null,
  is_real: false,
});

const handleFileData = (event) => {
  editedInvoiceItem.value.file = event.target.files[0];
};

function upload() {
  emits("upload", editedInvoiceItem.value);
}

function close() {
  dialogInvoice.value = false;
  editedInvoiceItem.value = Object.assign({}, defaultInvoiceItem.value);
}
</script>
