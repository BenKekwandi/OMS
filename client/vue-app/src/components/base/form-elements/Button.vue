<template>
    <v-btn :color="props.color" :variant="props.variant" :class="props.class">
        <template v-if="!(xs || sm)" v-slot:prepend>
            <v-icon>{{ props.icon }}</v-icon>
        </template>
        <div v-if="!(xs || sm)">{{ props.label }}</div>
        <template v-else-if="(xs || sm)">
            <v-icon>{{ props.icon }}</v-icon>
        </template>
    </v-btn>
</template>

<script setup>
import { useDisplay } from 'vuetify'

const { xs, sm } = useDisplay()


const props = defineProps({ color: String, variant: String, class: String, icon: String, label: String })
</script>