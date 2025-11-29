<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    overrides: {
        type: Object,
        default: () => ({}),
    },
    routeNameOverrides: {
        type: Object,
        default: () => ({}),
    },
});

const pathname = typeof window !== "undefined" ? window.location.pathname : "/";
const rawSegments = pathname.split("/").filter(Boolean);

const segments = computed(() => {
    return rawSegments;
});

function formatLabel(str) {
    if (!str) return "";
    const spaced = str
        .replace(/([a-z])([A-Z])/g, "$1 $2")
        .replace(/[_-]/g, " ")
        .replace(/\b\w/g, (c) => c.toUpperCase());
    return spaced;
}

function cumulativePath(index) {
    return "/" + rawSegments.slice(0, index + 1).join("/");
}

const breadcrumbs = computed(() => {
    const crumbs = [];

    segments.value.forEach((seg, idx) => {
        const path = cumulativePath(idx);
        const isLast = idx === segments.value.length - 1;

        const overrideByPath = props.overrides[path];
        const overrideByRouteName = props.routeNameOverrides?.[null];

        let label =
            typeof overrideByPath === "object" && overrideByPath.label
                ? overrideByPath.label
                : typeof overrideByPath === "string"
                ? overrideByPath
                : formatLabel(seg);

        let routeOverride =
            typeof overrideByPath === "object" && overrideByPath.route
                ? overrideByPath.route
                : null;

        crumbs.push({
            label,
            href: routeOverride || path,
            isLast,
        });
    });

    return crumbs;
});
</script>

<template>
    <nav
        class="text-lg font-robo mb-4 text-gray-600 flex items-center gap-2"
        aria-label="Breadcrumb"
    >
        <Link href="/" class="hover:underline text-indigo-600">Home</Link>
        <span>/</span>

        <template v-if="breadcrumbs.length === 0">
            <span class="text-gray-800 font-semibold">Home</span>
        </template>

        <template v-else>
            <template
                v-for="(crumb, i) in breadcrumbs"
                :key="crumb.href + '-' + i"
            >
                <template v-if="!crumb.isLast">
                    <Link
                        :href="crumb.href"
                        class="text-indigo-600 hover:underline"
                    >
                        {{ crumb.label }}
                    </Link>
                </template>
                <template v-else>
                    <span>{{
                        crumb.label
                    }}</span>
                </template>
                <span v-if="i < breadcrumbs.length - 1">/</span>
            </template>
        </template>
    </nav>
</template>

<style>
/*<Breadcrumb />*/
</style>
