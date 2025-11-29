<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch, nextTick } from "vue";
import { fas } from "@fortawesome/free-solid-svg-icons";
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

library.add(...Object.values(fas));

const props = defineProps({
    modelValue: String,
    label: String,
    error: String,
    placeholder: String,
});

const emit = defineEmits(["update:modelValue"]);

const search = ref("");
const showDropdown = ref(false);
const wrapperRef = ref(null);
const searchInputRef = ref(null);

watch(showDropdown, (visible) => {
  if (visible) {
    nextTick(() => {
      searchInputRef.value?.focus();
    });
  }
});

// Filter valid icons
const validIcons = Object.entries(fas)
    .filter(([_, icon]) => icon.iconName)
    .map(([_, icon]) => ({
        name: icon.iconName,
        class: `fas fa-${icon.iconName}`,
    }));

// Filter icons based on search
const iconList = computed(() => {
    const term = search.value?.toLowerCase().trim();
    if (!term) return validIcons.slice(0, 50);
    return validIcons.filter((icon) => icon.name.toLowerCase().includes(term));
});

// Select icon
function selectIcon(iconClass) {
    emit("update:modelValue", iconClass);
    showDropdown.value = false;
}

// Close dropdown on outside click
function handleClickOutside(event) {
    if (wrapperRef.value && !wrapperRef.value.contains(event.target)) {
        showDropdown.value = false;
    }
}

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});
</script>

<template>
    <div ref="wrapperRef" class="relative space-y-1">
        <label v-if="label" class="block text-sm font-medium text-gray-700">{{
            label
        }}</label>
        <input
            type="text"
            :value="modelValue"
            @focus="showDropdown = true"
            @input="$emit('update:modelValue', $event.target.value)"
            :placeholder="placeholder"
            class="w-full border rounded px-3 py-2"
        />
        <div v-if="error" class="text-sm text-red-600">{{ error }}</div>

        <div
            v-if="showDropdown"
            class="absolute z-10 w-full bg-white border mt-1 max-h-64 overflow-y-auto shadow-lg"
        >
            <input
                type="text"
                v-model="search"
                ref="searchInputRef"
                placeholder="Search icons..."
                class="w-full px-3 py-2 border-b"
                autofocus
            />
            <div class="grid grid-cols-6 gap-2 p-2">
                <div
                    v-for="(icon, index) in iconList"
                    :key="`${icon.name}-${index}`"
                    @click="selectIcon(icon.class)"
                    class="cursor-pointer text-center text-gray-700 hover:text-blue-600"
                >
                    <FontAwesomeIcon :icon="['fas', icon.name]" />
                    <div class="text-xs mt-1 truncate">{{ icon.class }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
/*<TextInput
    id="order_by"
    label="Order"
    v-model="form.order_by"
    :error="form.errors.order_by"
    placeholder="Order number..."
    @input="form.errors.order_by = null"
/>*/
</style>
