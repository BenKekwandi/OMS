<template>
    <v-menu :close-on-content-click="false" v-model="menu">
        <template v-slot:activator="{ props }">
            <v-text-field :color="color" :class="class" :label="label" v-model="dateString" readonly clearable
                append-inner-icon="mdi-calendar-month-outline" :variant="variant ? undefined : 'underlined'" v-bind="props"
                :rules="rules" :density="density" :placeholder="placeholder" :hide-details="hide_details" class="custom-placeholder-size" :max-width="maxWidth">
            </v-text-field>

        </template>
        <v-date-picker show-adjacent-months :color="color" hide-header @update:modelValue="(val) => formatDate(val)"
            max="2030-12-31" min="2023-01-01"></v-date-picker>
    </v-menu>
</template>

<script setup>
import { ref, watch } from "vue";
import format from "date-fns/format";

const props = defineProps({ class: String, color: String, label: String, variant: String, rules: Array, density: String, placeholder: String, maxWidth: String, hide_details: Boolean })

const dateString = defineModel({ type: String })
const emit = defineEmits("dateForRule")
const menu = ref(false)


const formatDate = (val) => {
    dateString.value = format(val, "do MMM yyyy");
    menu.value = false;

    emit("dateForRule", val)

};


</script>

<style >
.custom-placeholder-size input::placeholder {
  font-size: 14px;
}

</style>