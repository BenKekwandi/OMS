<template>
    <v-btn @click="exportItems" color="#66BB6A" :variant="props.variant" :class="props.class">
        <template v-if="!(xs || sm)" v-slot:prepend>
            <v-icon>mdi-file-download</v-icon>
        </template>
        <div v-if="!(xs || sm)">Export</div>
        <template v-else>
            <v-icon>mdi-file-download</v-icon>
        </template>
    </v-btn>
</template>

<script setup>
import { useDisplay } from 'vuetify'

const { xs, sm } = useDisplay()


const props = defineProps({ store: Object, role: String, headers: Array, variant: String, class: String })


async function exportItems() {
    if (props.role) {
        await props.store.handleExport(props.role);
    } else {
        await props.store.handleExport();
    }

    const headers = props.headers.slice();
    headers[0] = '\uFEFF' + headers[0];

    const csvContent =
        "data:text/csv;charset=utf-8," +
        encodeURIComponent(headers.join(",") + "\n" + props.store.csvData);

    const downloadLink = document.createElement("a");
    downloadLink.setAttribute("href", csvContent);
    downloadLink.setAttribute("download", props.store.name + ".csv");
    document.body.appendChild(downloadLink);

    downloadLink.click();

    document.body.removeChild(downloadLink);
}
</script>