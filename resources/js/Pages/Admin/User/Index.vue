<script setup>
import { useForm, Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import TextInput from "@/Shared/TextInput.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";
import Layout from "@/Shared/Layout.vue";
import SearchFilter from "@/Shared/SearchFilter.vue";
import { route } from "ziggy-js";
import Pagination from "@/Shared/Pagination.vue";
import Breadcrumb from "@/Shared/Breadcrumb.vue";

const props = defineProps({
    users: Object,
    roles: Array,
    permissions: Array,
    menuList: Array,
    search: String,
});

const selectedUser = ref(null);
const search = ref(props.search || "");

const form = useForm({
    id: null,
    name: "",
    email: "",
    password: "",
    roles: [],
    permissions: [],
    menus: [],
});

function editUser(user) {
    selectedUser.value = user;
    form.id = user.id;
    form.name = user.name;
    form.email = user.email;
    form.password = "";
    form.roles = user.roles.map((r) => r.name);
    form.permissions = user.permissions.map((p) => p.name);
    form.menus = user.menus.map((m) => m.id);
}

function resetForm() {
    selectedUser.value = null;
    form.reset();
}

function submit() {
    const method = form.id ? "put" : "post";
    const url = form.id
        ? route("admin.users.store", form.id)
        : route("admin.users.store");

    form[method](url, {
        preserveScroll: true,
        onSuccess: resetForm,
    });
}

function deleteUser(user) {
    if (confirm("Are you sure you want to delete this user?")) {
        useForm({}).delete(route("admin.users.destroy", user.id), {
            preserveScroll: true,
        });
    }
}

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
        <Head title="Create User Page" />
        <FlashMessage
            v-if="$page.props.flash?.status"
            :message="$page.props.flash.status"
            type="success"
        />
        <Layout>
            <Breadcrumb />
            <div class="flex justify-between gap-3 font-robo">
                <div
                    class="bg-white shadow rounded p-6 w-2/3 h-fit border border-gray-300"
                >
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold w-1/3">All Users</h2>
                        <div class="w-2/3">
                            <SearchFilter
                                v-model="search"
                                placeholder="Search Users..."
                                @search="fetchUsers"
                                @reset="resetSearch"
                            />
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
                            <tr
                                v-for="(user, i) in users.data"
                                :key="user.id"
                                class="border-t"
                            >
                                <td class="px-4 py-2">{{ i + 1 }}</td>
                                <td
                                    class="px-4 py-2"
                                    v-html="highlight(user.name)"
                                ></td>
                                <td
                                    class="px-4 py-2"
                                    v-html="highlight(user.email)"
                                ></td>
                                <td class="px-4 py-2" v-if="user.roles">
                                    <div class="flex flex-wrap">
                                        <span
                                            v-for="role in user.roles"
                                            :key="role.id || role.name"
                                            class="badge-success px-2 py-1 rounded text-sm"
                                        >
                                            {{ role.name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-2" v-if="user.permissions">
                                    <div class="flex flex-wrap">
                                        <span
                                            v-for="permission in user.permissions"
                                            :key="
                                                permission.id || permission.name
                                            "
                                            class="badge-success px-2 py-1 rounded text-sm"
                                        >
                                            {{ permission.name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-2" v-if="user.menus">
                                    <div class="flex flex-wrap">
                                        <span
                                            v-for="menu in user.menus"
                                            :key="menu.id || menu.title"
                                            class="badge-success px-2 py-1 rounded text-sm"
                                        >
                                            {{ menu.title }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <button
                                        @click="editUser(user)"
                                        class="text-blue-600 hover:underline"
                                    >
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button
                                        @click="deleteUser(user)"
                                        class="text-red-600 hover:underline ml-2"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <Pagination :links="users.links" />
                </div>

                <!-- Right: Form -->
                <div
                    class="bg-white shadow rounded p-6 w-1/3 h-fit border border-gray-300"
                >
                    <h2 class="text-lg font-bold mb-4">
                        {{ selectedUser ? "Edit User" : "Add User" }}
                    </h2>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div class="mb-4">
                            <TextInput
                                id="name"
                                label="Name"
                                v-model="form.name"
                                :error="form.errors.name"
                                placeholder="Name..."
                                @input="form.errors.name = null"
                            />
                        </div>

                        <div class="mb-4">
                            <TextInput
                                id="email"
                                label="Email"
                                type="email"
                                v-model="form.email"
                                :error="form.errors.email"
                                placeholder="Email..."
                                @input="form.errors.email = null"
                            />
                        </div>

                        <div class="mb-4">
                            <TextInput
                                id="password"
                                label="Password"
                                type="password"
                                v-model="form.password"
                                :error="form.errors.password"
                                placeholder="Password..."
                                @input="form.errors.password = null"
                            />
                        </div>

                        <div>
                            <label class="form-label">Roles</label>
                            <div class="flex items-center gap-8">
                                <label
                                    v-for="role in roles"
                                    :key="role"
                                    class="flex items-center space-x-2 select-none"
                                >
                                    <input
                                        type="checkbox"
                                        :value="role"
                                        v-model="form.roles"
                                    />
                                    <span>
                                        {{ role }}
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="form-label"
                                >All Menus And Permissions</label
                            >
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div
                                    v-for="menu in menuList"
                                    :key="menu.id"
                                    class="mb-1"
                                >
                                    <label class="select-none">
                                        <input
                                            type="checkbox"
                                            :value="menu.id"
                                            v-model="form.menus"
                                        />
                                        {{ menu.title }}
                                    </label>

                                    <div
                                        v-if="form.menus.includes(menu.id)"
                                        class="ml-6 mt-2"
                                    >
                                        <div
                                            v-for="permission in menu.permissions"
                                            :key="permission.id"
                                        >
                                            <label class="select-auto">
                                                <input
                                                    type="checkbox"
                                                    :value="permission.name"
                                                    v-model="form.permissions"
                                                />
                                                {{ permission.name }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-2 justify-end border-t pt-5">
                            <button
                                type="submit"
                                class="btn rounded-sm fw-normal flex justify-center items-center gap-4"
                                :class="{ 'btn-spinner': form.processing }"
                                :disabled="form.processing"
                            >
                                <span>{{
                                    form.processing ? "Processing..." : "Save"
                                }}</span>
                                <span
                                    v-if="form.processing"
                                    class="spinner"
                                ></span>
                            </button>
                            <button
                                type="button"
                                @click="resetForm"
                                class="btn-danger"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Layout>
    </div>
</template>
