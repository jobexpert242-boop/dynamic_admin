<script setup>
import { ref, onMounted } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

const props = defineProps({
    menu: Object,
    direction: { type: String, default: "vertical" },
    level: { type: Number, default: 0 },
    collapsed: { type: Boolean, default: false },
});

const page = usePage();
const open = ref(false);

function isActive(route) {
    if (!route || route === "#") return false;

    const current = page.url.replace(/\/+$/, "");
    const target = "/" + route.replace(/\/+$/, "");

    return current === target;
}

function toggle() {
    open.value = !open.value;
}

onMounted(() => {
    if (props.menu?.children?.some((child) => isActive(child.route))) {
        open.value = true;
    }
});
</script>

<template>
    <div class="relative mb-2" :style="{ marginLeft: level * 8 + 'px' }">
        <component
            :is="!menu.children.length ? Link : 'div'"
            :href="!menu.children.length ? '/' + menu.route : undefined"
            @click="menu.children.length && toggle()"
            class="flex items-center justify-between px-4 py-2 cursor-pointer hover:bg-indigo-700 rounded"
            :class="{
                'bg-indigo-700 text-white': isActive(menu.route),
            }"
        >
            <div class="flex items-center gap-2">
                <i :class="menu.icon"></i>
                <span v-if="!collapsed">{{ menu.title }}</span>
            </div>

            <i
                v-if="menu.children.length"
                class="fas fa-chevron-down transition-transform duration-300"
                :class="{ 'rotate-180': open }"
            />
        </component>

        <div v-if="open" class="ml-3 mt-1 space-y-1">
            <RecursiveMenu
                v-for="child in menu.children"
                :key="child.id"
                :menu="child"
                :level="level + 1"
                :direction="direction"
                :collapsed="collapsed"
            />
        </div>
    </div>
</template>
