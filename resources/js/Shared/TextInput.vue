<script setup>
import { ref, defineProps, defineEmits } from "vue";

defineProps({
    label: String,
    modelValue: [String, Number],
    type: {
        type: String,
        default: "text",
    },
    placeholder: String,
    error: String,
});

const emit = defineEmits(["update:modelValue", "input"]);

const showPassword = ref(false);
function togglePassword() {
    showPassword.value = !showPassword.value;
}
function onInput(event) {
    const value = event.target.value;
    emit("update:modelValue", value);
    emit("input", value);
}
</script>

<template>
    <div :class="$attrs.class" class="relative">
        <label v-if="label" class="form-label font-robo">{{ label }}</label>

        <div class="relative">
            <input
                :type="showPassword ? 'text' : type"
                :placeholder="placeholder"
                :value="modelValue"
                @input="onInput"
                class="w-full py-2 px-3 border border-gray-400 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-gray-700 font-robo transition duration-200 form-input"
                v-bind="$attrs"
                :class="{ error: error }"
            />

            <!-- Toggle Button -->
            <button
                v-if="type === 'password'"
                type="button"
                @click="togglePassword"
                class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700"
            >
                <i
                    :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"
                ></i>
            </button>

            <!-- error icon  -->
            <div
                v-if="error"
                class="absolute inset-y-0 right-0 px-3 flex items-center text-red-600"
            >
                <i class="fas fa-exclamation-circle"></i>
            </div>
        </div>

        <div v-if="error" class="form-error">{{ error }}</div>
    </div>
</template>
<style>
/* <TextInput
    id="email"
    label="Email"
    type="email"
    v-model="form.email"
    :error="form.errors.email"
    placeholder="Email..."
    @input="form.errors.email = null"
/>*/
</style>
