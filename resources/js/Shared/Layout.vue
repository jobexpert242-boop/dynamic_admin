<script setup>
import { usePage, Link } from "@inertiajs/vue3";
import RecursiveMenu from "@/Shared/RecursiveMenu.vue";
import { route } from "ziggy-js";
import { computed, onMounted, onUnmounted, ref } from "vue";
import Header from "../Components/Header.vue";
import Language from "../Components/Language.vue";
import Notification from "../Components/Notification.vue";
import AccessibilityPanel from "../Components/AccessibilityPanel.vue";
import FontSizeBar from "../Components/FontSizeBar.vue";
import DarkModeToggle from "../Components/DarkModeToggle.vue";

const page = usePage();
const props = defineProps({
    menus: Object,
    auth: Object,
});

const auth = props.auth || page.props.auth || {};
const menus = props.menus || page.props.menus || {};
const currentYear = new Date().getFullYear();
const collapsed = ref(false);
const searchTerm = ref("");
const animatedPlaceholder = ref("");

function filterRecursive(menusArray, term) {
    return menusArray
        .map((menu) => {
            const children = menu.children
                ? filterRecursive(menu.children, term)
                : [];

            const matches = menu.title
                .toLowerCase()
                .includes(term.toLowerCase());

            if (matches || children.length) {
                return { ...menu, children };
            }
            return null;
        })
        .filter(Boolean);
}

const filteredMenus = computed(() => {
    if (!searchTerm.value) {
        return menus.left;
    }
    return filterRecursive(menus.left, searchTerm.value);
});

const placeholderText = "Search Menu...";
let intervalId;
onMounted(() => {
    let i = 0;
    let typingForward = true;

    intervalId = setInterval(() => {
        if (typingForward) {
            animatedPlaceholder.value = placeholderText.slice(0, i + 1);
            i++;
            if (i === placeholderText.length) {
                typingForward = false;
            }
        } else {
            animatedPlaceholder.value = placeholderText.slice(0, i);
            i--;
            if (i === 0) {
                typingForward = true;
            }
        }
    }, 200);
});

onUnmounted(() => {
    clearInterval(intervalId);
});
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <!-- Top Menu -->
        <header
            class="bg-white border-b px-6 py-6 flex justify-between items-center"
        >
            <h1 class="leading-0 font-robo text-2xl font-bold text-black">
                <Link :href="route('admin.dashboard')"
                    ><img
                        :src="`/storage/${$page.props.site?.logo}`"
                        class="h-12"
                        alt="ComitsBD Admin"
                /></Link>
            </h1>

            <RecursiveMenu
                v-for="menu in menus.top"
                :key="menu.id"
                :menu="menu"
                direction="horizontal"
            />

            <div class="flex gap-5 items-center">
                <Language />
                <DarkModeToggle />
                <Header />
                <Notification />
            </div>
        </header>

        <div class="flex flex-1">
            <!-- Left Sidebar -->
            <aside
                :class="[
                    'text-white p-4 transition-all duration-300 border-r',
                    collapsed ? 'w-30' : 'w-56',
                ]"
            >
                <div class="relative w-full max-w-md mb-2">
                    <div
                        class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
                    >
                        <i
                            class="fa-solid fa-magnifying-glass text-gray-400"
                        ></i>
                    </div>
                    <input
                        type="search"
                        v-model="searchTerm"
                        :placeholder="animatedPlaceholder"
                        class="search_css"
                    />
                </div>

                <span
                    class="rounded mb-2 flex justify-end items-end w-full cursor-w-resize"
                    @click="collapsed = !collapsed"
                >
                    <i
                        class="fa-solid fa-arrow-right-arrow-left bg-slate-400 px-4 py-1 rounded"
                        style="display: flex; justify-content: center"
                    ></i>
                </span>
                <RecursiveMenu
                    v-for="menu in filteredMenus"
                    :key="menu.id"
                    :menu="menu"
                    direction="vertical"
                    :collapsed="collapsed"
                />
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6 overflow-y-auto">
                <slot />
            </main>
        </div>

        <!-- Footer -->
        <footer
            class="bg-white border-t px-6 py-4 flex justify-center items-center gap-10"
        >
            <RecursiveMenu
                v-for="menu in menus.footer"
                :key="menu.id"
                :menu="menu"
                direction="horizontal"
            />
            <p>
                All Rights Reserved
                <Link
                    href="https://comitsbd.com/"
                    class="text-indigo-600 hover:underline"
                    >ComitsBD</Link
                >
                | {{ currentYear }}
            </p>
        </footer>
        <AccessibilityPanel />
        <FontSizeBar />
    </div>
</template>
