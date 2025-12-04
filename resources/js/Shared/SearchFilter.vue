<script setup>
import { ref, watch } from "vue";

const props = defineProps({
    modelValue: String,
    placeholder: { type: String, default: "Search…" },
});

const emit = defineEmits(["update:modelValue", "search", "reset"]);

const keyword = ref(props.modelValue || "");

watch(
    () => props.modelValue,
    (val) => {
        keyword.value = val || "";
    }
);

watch(keyword, (val) => {
    emit("update:modelValue", val);
    emit("search", val);
});
</script>

<template>
    <div
        class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 w-full"
    >
        <div class="relative w-full">
            <div
                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
            >
                <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
            </div>
            <input
                type="search"
                v-model="keyword"
                :placeholder="placeholder"
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500 text-gray-700 font-robo transition duration-200"
            />
        </div>

        <div class="flex items-center gap-4">
            <slot name="filters" />
            <button
                class="text-gray-500 hover:text-gray-700 text-sm border rounded border-gray-200 p-2"
                type="button"
                @click="$emit('reset')"
            >
                <i class="fa-solid fa-rotate-right"></i>
            </button>
        </div>
    </div>
</template>

<style>
/*
function fetchUsers() {
    router.get(
        "/admin/users",
        { search: search.value },
        { preserveState: true, replace: true }
    );
}
function resetSearch() {
    search.value = "";
    router.get("/admin/users", {}, { preserveState: false, replace: true });
}
function highlight(text) {
    const keyword = search.value.trim();
    if (!keyword) return text;

    const escapedKeyword = keyword.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
    const regex = new RegExp(`(${escapedKeyword})`, "gi");

    return text.replace(
        regex,
        '<span class="bg-yellow-500">$1</span>'
    );
}
<SearchFilter
    v-model="search"
    placeholder="Search Users..."
    @search="fetchUsers"
    @reset="resetSearch"
/>
*/
</style>
