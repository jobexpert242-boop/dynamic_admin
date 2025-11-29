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
        <!-- Search input -->
        <div class="flex w-full bg-white rounded shadow">
            <input
                class="form-input font-robo"
                type="text"
                :placeholder="placeholder"
                v-model="keyword"
            />
        </div>

        <!-- Filters slot -->
        <div class="flex items-center gap-4">
            <slot name="filters" />

            <!-- Reset button at the end -->
            <button
                class="text-gray-500 hover:text-gray-700 text-sm border rounded border-gray-500 p-2"
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
