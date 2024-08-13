<template>
    <v-dialog v-model="dialogDelete" max-width="450px">
        <v-card class="pa-3">
            <v-card-title class="text-center">Are you sure you want to <br />
                {{ type }} selected {{ item }}(s)?</v-card-title>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue-darken-1" variant="text" @click="emits('close')">
                    Cancel
                </v-btn>
                <v-btn color="blue-darken-1" :loading="loading" variant="text" @click="emits('confirm')">
                    OK
                </v-btn>
                <v-spacer></v-spacer>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup>
import { ref } from 'vue';

const dialogDelete = defineModel({ type: Boolean })

const props = defineProps({ loading: Boolean, item: String, type: String })

const emits = defineEmits(["confirm", "close"])
</script>