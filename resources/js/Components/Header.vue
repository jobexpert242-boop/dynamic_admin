<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import Dropdown from "@/Shared/Dropdown.vue";

const props = defineProps({
    menus: Object,
    auth: Object,
    avatar: String,
});
const page = usePage();
const auth = props.auth || page.props.auth || {};
</script>

<template>
    <Dropdown>
        <template #default>
            <div class="flex items-center cursor-pointer group">
                <span class="text-gray-700 group-hover:text-indigo-600">
                    {{ auth.user?.name }}
                </span>
                <img :src="auth.user?.image ? `/storage/${auth.user.image}` : auth.avatar"
                    class="w-10 h-10 rounded-full object-cover ml-2"
                />
                <svg
                    class="w-5 h-5 ml-1 fill-gray-700 group-hover:fill-indigo-600"
                    viewBox="0 0 20 20"
                >
                    <path d="M5.5 7l4.5 4.5L14.5 7z" />
                </svg>
            </div>
        </template>
        <template #dropdown>
            <div class="mt-2 py-2 text-md bg-white rounded shadow-xl border border-gray-400">
                <Link
                    class="block px-6 py-2 hover:bg-indigo-500 hover:text-white"
                    :href="route('admin.profile')"
                    >My Profile</Link
                >
                <Link
                    class="block px-6 py-2 hover:bg-indigo-500 hover:text-white text-left w-full"
                    method="post"
                    as="button"
                    :href="route('admin.logout')"
                    >Logout</Link
                >
            </div>
        </template>
    </Dropdown>
</template>
