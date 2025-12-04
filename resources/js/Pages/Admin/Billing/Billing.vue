<script setup>
import { useForm, Head, router, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import TextInput from "@/Shared/TextInput.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";
import Layout from "@/Shared/Layout.vue";
import SearchFilter from "@/Shared/SearchFilter.vue";
import { route } from "ziggy-js";
import Pagination from "@/Shared/Pagination.vue";
import Breadcrumb from "@/Shared/Breadcrumb.vue";

const props = defineProps({
    search: String,
});

const search = ref(props.search || "");

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

    return text.replace(regex, '<span class="bg-yellow-500">$1</span>');
}
</script>

<template>
    <div>
        <Head title="Billing Page" />
        <FlashMessage
            v-if="$page.props.flash?.status"
            :message="$page.props.flash.status"
            type="success"
        />
        <Layout>
            <Breadcrumb />
            <div
                class="bg-white shadow rounded p-6 h-fit border border-gray-300"
            >
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold w-1/4">All Users</h2>
                    <div class="w-2/4">
                        <SearchFilter
                            v-model="search"
                            placeholder="Search Users..."
                            @search="fetchUsers"
                            @reset="resetSearch"
                        />
                    </div>
                    <div class="w-1/4 text-end">
                        <Link :href="route('admin.createInvoice')" class="btn rounded-sm">Add Invoice</Link>
                    </div>
                </div>
                <table class="min-w-full table-auto border border-gray-400">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">#</th>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">Roles</th>
                            <th class="px-4 py-2 text-left">Permissions</th>
                            <th class="px-4 py-2 text-left">Menus</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Layout>
    </div>
</template>
