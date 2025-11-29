<script setup>
import { useForm, Head, router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import TextInput from "@/Shared/TextInput.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";
import Layout from "@/Shared/Layout.vue";
import SearchFilter from "@/Shared/SearchFilter.vue";
import Pagination from "@/Shared/Pagination.vue";
import Breadcrumb from "@/Shared/Breadcrumb.vue";

const props = defineProps({ roles: Object, permissions: Array });
const editing = ref(null);

const form = useForm({
    name: "",
    permissions: [],
});

function submit() {
    if (editing.value) {
        form.put(`/admin/roles/${editing.value.id}`, {
            onSuccess: () => {
                editing.value = null;
                form.reset();
            },
        });
    } else {
        form.post("/admin/roles", {
            onSuccess: () => form.reset(),
        });
    }
}

function edit(role) {
    editing.value = role;
    form.name = role.name;
    form.permissions = role.permissions.map((p) => p.name);
}

function remove(role) {
    if (confirm("Delete this role?")) {
        form.delete(`/admin/roles/${role.id}`);
    }
}

// search
const page = usePage();
const search = ref(page.props.search || "");
const selectedRole = ref(page.props.role || "");
const roleOptions = page.props.roleOptions || [];

function fetchRoles() {
    router.get(
        "/admin/roles",
        {
            search: search.value,
            role: selectedRole.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    );
}

function resetSearch() {
    search.value = "";
    selectedRole.value = "";
    router.get(
        "/admin/roles",
        {},
        {
            preserveState: false,
            replace: true,
        }
    );
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
        <Head title="Role Page" />
        <FlashMessage
            v-if="$page.props.flash?.status"
            :message="$page.props.flash.status"
            type="success"
        />
        <Layout>
        <Breadcrumb/>
            <div class="flex justify-between gap-3 font-robo">
                <!-- Role List -->
                <div class="bg-white shadow rounded p-6 w-2/3 h-fit border border-gray-300">
                    <div class="flex justify-between">
                        <h2 class="text-lg font-semibold mb-4 w-1/3">
                            All Roles
                        </h2>
                        <div class="w-2/3">
                            <SearchFilter
                                v-model="search"
                                placeholder="Search Roles..."
                                @search="fetchRoles"
                                @reset="resetSearch"
                            >
                                <template #filters>
                                    <select
                                        v-model="selectedRole"
                                        @change="fetchRoles"
                                        class="border rounded px-4 py-2"
                                    >
                                        <option value="">All Roles</option>
                                        <option
                                            v-for="role in roleOptions"
                                            :key="role"
                                            :value="role"
                                        >
                                            {{ role }}
                                        </option>
                                    </select>
                                </template>
                            </SearchFilter>
                        </div>
                    </div>
                    <table class="min-w-full table-auto border border-gray-400">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left">#</th>
                                <th class="px-4 py-2 text-left">Role</th>
                                <th class="px-4 py-2 text-left">Permissions</th>
                                <th class="px-4 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(role, i) in roles.data"
                                :key="role.id"
                                class="border-t"
                            >
                                <td class="px-4 py-2">{{ i + 1 }}</td>
                                <td class="px-4 py-2" v-html="highlight(role.name)"></td>
                                <td class="px-4 py-2">
                                    <span
                                        v-for="perm in role.permissions"
                                        :key="perm.id"
                                        class="badge-success"
                                    >
                                        {{ perm.name }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <button
                                        @click="edit(role)"
                                        class="text-blue-600 hover:underline mr-2"
                                    >
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button
                                        @click="remove(role)"
                                        class="text-red-600 hover:underline"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <Pagination :links="roles.links" />
                </div>

                <!-- Role Form -->
                <div class="bg-white shadow rounded p-6 w-1/3 h-fit border border-gray-300">
                    <h2 class="text-lg font-semibold mb-4">
                        {{ editing ? "Edit Role" : "Create Role" }}
                    </h2>
                    <form @submit.prevent="submit" class="space-y-4">
                        <TextInput
                            id="name"
                            label="Role Name"
                            v-model="form.name"
                            :error="form.errors.name"
                            placeholder="Enter Role Name"
                            @input="form.errors.name = null"
                        />

                        <div>
                            <label class="block font-medium mb-2"
                                >Assign Permissions</label
                            >
                            <div class="grid grid-cols-2 gap-2">
                                <label
                                    v-for="permission in permissions"
                                    :key="permission.id"
                                    class="flex items-center space-x-2"
                                >
                                    <input
                                        type="checkbox"
                                        :value="permission.name"
                                        v-model="form.permissions"
                                    />
                                    <span>{{ permission.name }}</span>
                                </label>
                            </div>
                        </div>

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
