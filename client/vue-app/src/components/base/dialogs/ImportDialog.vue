<template>
  <v-dialog v-model="dialogImport" max-width="500px">
    <v-card class="pa-3">
      <v-card-title class="text-h5 text-center"
        >Import an {{ name }}(s)?</v-card-title
      >
      <v-card-text>
        <v-row>
          <v-col cols="12">
            <v-file-input
              label="Import file"
              @change="handleFileData"
            ></v-file-input>
          </v-col>
        </v-row>
        <v-col cols="12" class="text-center">
          <Button
            color="#66BB6A"
            @click="exportExample"
            variant="flat"
            class="mr-2"
            label="export example"
            icon="mdi-file-download"
          />
        </v-col>
      </v-card-text>
      <v-card-actions class="mx-4 my-4">
        <v-spacer></v-spacer>
        <v-btn
          class="px-4"
          color="blue-darken-1"
          variant="outlined"
          @click="close"
          >Cancel</v-btn
        >
        <v-btn
          class="px-6"
          color="blue-darken-1"
          variant="elevated"
          @click="upload"
          >upload</v-btn
        >
        <v-spacer></v-spacer>
      
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { ref } from "vue";
import Button from "../form-elements/Button.vue";

const dialogImport = defineModel({ type: Boolean });

const props = defineProps({ name: String, importHeaders: Array });

const emits = defineEmits(["upload"]);

const file = ref({
  file: null,
});

const handleFileData = (event) => {
  file.value.file = event.target.files[0];
};

function upload() {
  emits("upload", file.value);
}
const exportExample = () => {
  const csvData = convertArrayToCSV(props.importHeaders)

  // Create a Blob object
  const blob = new Blob([csvData], { type: "text/csv;charset=utf-8" });

  // Create a link element
  const link = document.createElement("a");
  link.href = window.URL.createObjectURL(blob);
  link.setAttribute("download", "example.csv");

  // Simulate a click to trigger the download
  link.click();
};

const convertArrayToCSV = (arr) => {
  const header = arr.join(",") + "\n";
  return header;
};

function close() {
  dialogImport.value = false;
  file.value = Object.assign({}, null);
}
</script>
