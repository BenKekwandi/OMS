<template>
    <v-sheet id="img-section" class="profile" :style="margins ? 'margin: 25px' : undefined"
        :max-width="size ? size : 250" :width="size ? size : 250" :height="size ? size : 250"
        @dragover.prevent="onDragOver" @dragleave.prevent="onDragLeave" @drop.prevent="onDrop">
        <v-img class="profile_image" :src="imageUrl"></v-img>
        <div class="profile_content" v-if="!readonly">
            <span class="profile_text"></span>
            <div class="button-text">
                <span>Drag & Drop or
                    <a type="button" @click="openFileInput" class="browse-btn">Browse</a></span>
            </div>
            <input @change="handleFileUpload" type="file" accept="image/*" ref="fileInput" class="d-none" />
        </div>
    </v-sheet>
</template>

<script setup>
import { ref, defineEmits, toRefs, computed, watch } from "vue";
const props = defineProps({ image: String, readonly: Boolean, size: Number, margins: Boolean });

const { image, readonly, size, margins } = toRefs(props);



const imageUrl = ref('')
const fileInput = ref(null);
const file = ref("");
const emit = defineEmits(["imageData"]);


const serverImage = computed(() => {
    return image.value
})

watch(serverImage, (value) => {

    if (value === '') {
        imageUrl.value = '/default-img.png'
    } else {
        imageUrl.value = value
    }
}, { immediate: true })


const onDragOver = (event) => {
    event.preventDefault();
    event.dataTransfer.dropEffect = "copy";
};

const onDragLeave = (event) => {
    event.preventDefault();
};

const onDrop = (event) => {
    event.preventDefault();
    file.value = event.dataTransfer.files[0];
    imageUrl.value = URL.createObjectURL(file.value);
    emit("imageData", file.value);
};

const openFileInput = () => {
    fileInput.value.click();
};

const handleFileUpload = (event) => {
    imageUrl.value = {}
    file.value = event.target.files[0];
    imageUrl.value = URL.createObjectURL(file.value);
    emit("imageData", file.value);
};
</script>

<style scoped>
.profile {
    position: relative;
    overflow: hidden;
    border-radius: 6px;
    border-style: dashed;
    border-width: 2px;
    background-color: #f9f7f7;
    display: flex;
    justify-content: center;
    align-items: center;
}

.profile:hover .profile_content {
    opacity: 1;
}

.profile:hover .profile_image {
    opacity: 0.5;
}

.profile_image {
    object-fit: cover;
    opacity: 1;
    transition: opacity 0.2s ease-in-out;
}

.profile_content {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: white;
    opacity: 0;
    transition: opacity 0.2s ease-in-out;
}

.profile_text {
    height: 220px;
}

.button-text {
    background-color: #3f72af;
    width: 100%;
    padding: 10px;
    text-align: center;
}

.browse-btn {
    text-decoration: underline;
}

.dragging-over {
    border-color: #3f72af;
}
</style>
