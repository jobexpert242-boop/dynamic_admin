<script setup>
import { ref } from "vue";

const props = defineProps({
    modelValue: File || Array,
    label: String,
    accept: { type: String, default: "" },
    multiple: { type: Boolean, default: false },
    error: String,
});

const emit = defineEmits(["update:modelValue"]);

const fileInput = ref(null);
const fileName = ref("");
const previewUrl = ref("");

function triggerBrowse() {
    fileInput.value.click();
}

function handleFileChange(event) {
    const files = event.target.files;
    if (props.multiple) {
        const fileArray = Array.from(files);
        emit("update:modelValue", fileArray);
        fileName.value = fileArray.map((f) => f.name).join(", ");
        previewUrl.value = fileArray[0]
            ? URL.createObjectURL(fileArray[0])
            : "";
    } else {
        const file = files[0];
        emit("update:modelValue", file);
        fileName.value = file?.name || "";
        previewUrl.value = file ? URL.createObjectURL(file) : "";
    }
}

function handleDrop(event) {
    const files = event.dataTransfer.files;
    if (props.multiple) {
        const fileArray = Array.from(files);
        emit("update:modelValue", fileArray);
        fileName.value = fileArray.map((f) => f.name).join(", ");
        previewUrl.value = fileArray[0]
            ? URL.createObjectURL(fileArray[0])
            : "";
    } else {
        const file = files[0];
        emit("update:modelValue", file);
        fileName.value = file?.name || "";
        previewUrl.value = file ? URL.createObjectURL(file) : "";
    }
}

function removeFile() {
    emit("update:modelValue", props.multiple ? [] : null);
    fileInput.value.value = null;
    fileName.value = "";
    previewUrl.value = "";
}
</script>

<template>
    <div>
        <label
            v-if="label"
            class="form-label text-sm font-medium text-gray-700 mb-1 block"
        >
            {{ label }}
        </label>

        <div
            class="form-input p-0 border-2 border-dashed border-gray-300 rounded-md cursor-pointer bg-white"
            @dragover.prevent
            @drop.prevent="handleDrop"
            @click="triggerBrowse"
        >
            <input
                ref="fileInput"
                type="file"
                class="hidden"
                :accept="accept"
                :multiple="multiple"
                @change="handleFileChange"
            />

            <div class="p-4 text-center text-gray-500 text-sm">
                <p v-if="!fileName">
                    Drag & drop or
                    <span class="underline text-blue-600">browse</span> to
                    upload
                </p>
                <p v-else class="text-xs text-gray-700 font-medium">
                    {{ fileName }}
                </p>
            </div>
        </div>

        <div class="bg-slate-500 rounded">
            <div v-if="fileName" class="flex justify-between items-center mt-2">
                <span class="text-xs text-gray-500">{{ fileName }}</span>
                <button
                    type="button"
                    class="px-2 py-1 text-xs text-white bg-red-500 hover:bg-red-700 rounded"
                    @click="removeFile"
                >
                    <i class="fa fa-trash"></i>
                </button>
            </div>

            <div v-if="previewUrl" class="mt-2">
                <img
                    :src="previewUrl"
                    class="w-20 h-20 rounded-full object-cover"
                />
            </div>
        </div>

        <div v-if="error" class="form-error text-red-500 text-xs mt-1">
            {{ error }}
        </div>
    </div>
</template>

<style scoped>
.form-input {
    transition: border-color 0.2s ease;
}
.form-input:hover {
    border-color: #4b5563;
}
/*
import FileInput from "@/Shared/FileInput.vue";
<FileInput
  v-model="form.avatar"
  label="Profile Picture"
  accept="image/*"
  :multiple="false"
  :error="form.errors.avatar"
/>
*/
</style>
