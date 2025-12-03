<script setup>
import { ref, watch, onMounted } from "vue";

const darkMode = ref(false);
const KEY_DARK = "accessibility:dark";

const applyDarkMode = (val) => {
    document.documentElement.classList.toggle("dark", !!val);
};

const toggleQuickDark = () => {
    darkMode.value = !darkMode.value;
};

watch(darkMode, (val) => {
    applyDarkMode(val);
    localStorage.setItem(KEY_DARK, val ? "true" : "false");
});
onMounted(() => {
    const savedDark = localStorage.getItem(KEY_DARK);
    darkMode.value = savedDark === "true";
    applyDarkMode(darkMode.value);
});
</script>

<template>
    <button
        @click="toggleQuickDark"
        class="mb-1 btn rounded-full fw-normal shadow-lg"
        title="Toggle Dark Mode"
    >
        <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'"></i>
    </button>
</template>
