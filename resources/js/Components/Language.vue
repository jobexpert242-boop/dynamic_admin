<script setup>
import { router } from "@inertiajs/vue3";
import { ref } from "vue";

const open = ref(false);
const selectedLang = ref(localStorage.getItem("lang") || "en");

const languages = [
    { code: "en", label: "En", icon: "fa-flag-usa" },
    { code: "bn", label: "Bn", icon: "fa-flag" },
];

const current = ref(languages.find((l) => l.code === selectedLang.value));

function selectLang(lang) {
    selectedLang.value = lang.code;
    current.value = lang;
    open.value = false;
    localStorage.setItem("lang", lang.code);
    router.visit(`/lang/${lang.code}`, { preserveState: true, replace: true });
}
</script>

<template>
    <div>
        <div class="relative w-25">
            <button
                @click="open = !open"
                class="w-full px-2 py-1 border rounded flex justify-between items-center"
            >
                <span>
                    <i :class="`fas ${current.icon}`" class="mr-2"></i>
                    {{ current.label }}
                </span>
                <i class="fas fa-chevron-down"></i>
            </button>
            <ul
                v-if="open"
                class="absolute z-10 bg-white border mt-1 w-full rounded shadow"
            >
                <li
                    v-for="lang in languages"
                    :key="lang.code"
                    @click="selectLang(lang)"
                    class="px-2 py-1 hover:bg-gray-300 cursor-pointer flex items-center"
                >
                    <i :class="`fas ${lang.icon}`" class="mr-2"></i>
                    {{ lang.label }}
                </li>
            </ul>
        </div>
    </div>
</template>
