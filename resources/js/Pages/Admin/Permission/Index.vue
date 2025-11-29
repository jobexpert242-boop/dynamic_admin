<script setup>
import { useForm, Head, router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import TextInput from "@/Shared/TextInput.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";
import Layout from "@/Shared/Layout.vue";
import SearchFilter from "@/Shared/SearchFilter.vue";
import Pagination from "@/Shared/Pagination.vue";
import Breadcrumb from "@/Shared/Breadcrumb.vue";

const props = defineProps({ permissions: Object });
const editing = ref(null);

const form = useForm({ name: "" });

function submit() {
    if (editing.value) {
        form.put(`/admin/permissions/${editing.value.id}`, {
            onSuccess: () => {
                editing.value = null;
                form.reset();
            },
        });
    } else {
        form.post("/admin/permissions", {
            onSuccess: () => form.reset(),
        });
    }
}

function edit(permission) {
    editing.value = permission;
    form.name = permission.name;
}

function remove(permission) {
    if (confirm("Delete this permission?")) {
        form.delete(`/admin/permissions/${permission.id}`);
    }
}

// search
const page = usePage();
const search = ref(page.props.search || "");

function fetchUsers() {
    router.get(
        "/admin/permissions",
        { search: search.value },
        { preserveState: true, replace: true }
    );
}

function resetSearch() {
  router.get('/admin/permissions', {}, {
    preserveState: false,
    replace: true,
  })
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
// search end
</script>

<template>
    <div>
        <Head title="Permission Page" />
        <FlashMessage
            v-if="$page.props.flash?.status"
            :message="$page.props.flash.status"
            type="success"
        />
        <Layout>
        <Breadcrumb/>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Permission List -->
                <div class="bg-white shadow rounded p-6 h-fit border border-gray-300">
                    <div class="flex justify-between">
                        <h2 class="text-lg font-semibold mb-4 w-1/2">
                            All Permissions
                        </h2>
                        <div class="w-1/2">
                        <SearchFilter
                            v-model="search"
                            placeholder="Search permissions…"
                            @search="fetchUsers"
                            @reset="resetSearch"
                        />
                        </div>
                    </div>
                    <table
                        class="min-w-full table-auto border border-gray-400 bg-white rounded shadow"
                    >
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left">#</th>
                                <th class="px-4 py-2 text-left">
                                    Permission Name
                                </th>
                                <th class="px-4 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(permission, i) in permissions.data"
                                :key="permission.id"
                                class="border-t border-gray-400 hover:bg-gray-50"
                            >
                                <td class="px-4 py-2">{{ i + 1 }}</td>
                                <td class="px-4 py-2 font-medium text-gray-800" v-html="highlight(permission.name)"></td>
                                <td class="px-4 py-2">
                                    <button
                                        @click="edit(permission)"
                                        class="text-blue-600 hover:underline mr-3"
                                    >
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button
                                        @click="remove(permission)"
                                        class="text-red-600 hover:underline"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <Pagination :links="permissions.links" />
                </div>

                <!-- Permission Form -->
                <div class="bg-white shadow rounded p-6 h-fit border border-gray-300">
                    <h2 class="text-lg font-semibold mb-4">
                        {{ editing ? "Edit Permission" : "Create Permission" }}
                    </h2>
                    <form @submit.prevent="submit" class="space-y-4">
                        <TextInput
                            id="name"
                            label="Permission Name"
                            v-model="form.name"
                            :error="form.errors.name"
                            placeholder="Enter Permission Name"
                            @input="form.errors.name = null"
                        />
                        <button
                            type="submit"
                            class="btn-success"
                        >
                            {{ editing ? "Update" : "Create" }}
                        </button>
                    </form>
                </div>
            </div>
        </Layout>
    </div>
</template>
