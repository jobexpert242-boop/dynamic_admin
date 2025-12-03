<script setup>
import { useForm, Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import TextInput from "@/Shared/TextInput.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";
import Layout from "@/Shared/Layout.vue";
import SearchFilter from "@/Shared/SearchFilter.vue";
import SelectInput from "@/Shared/SelectInput.vue";
import IconPicker from "@/Shared/IconPicker.vue";
import Pagination from "@/Shared/Pagination.vue";
import Breadcrumb from "@/Shared/Breadcrumb.vue";

const props = defineProps({
    menuList: Object,
    allPermissions: Array,
    allMenus: Array,
    search: String,
});

const editing = ref(null);
const search = ref(props.search || "");
const form = useForm({
    title: "",
    parent_id: "",
    permission_class: [],
    route: "#",
    icon: "",
    status: true,
    //admin_left_section: false,
    top: false,
    left: true,
    footer: false,
    order_by: "",
});

function submit() {
    if (editing.value) {
        form.put(`/admin/menus/${editing.value.id}`, {
            onSuccess: () => {
                editing.value = null;
                form.reset();
            },
        });
    } else {
        form.post("/admin/menus", {
            onSuccess: () => {
                form.reset(), location.reload();
            },
        });
    }
}

function edit(menu) {
    editing.value = menu;
    form.title = menu.title;
    form.route = menu.route;
    form.icon = menu.icon;
    form.parent_id = menu.parent_id;
    form.permission_class = Array.isArray(menu.permission_class)
        ? menu.permission_class
        : menu.permission_class
        ? [menu.permission_class]
        : [];
    form.status = !!menu.status;
    // form.admin_left_section = !!menu.admin_left_section;
    form.top = !!menu.top;
    form.left = !!menu.left;
    form.footer = !!menu.footer;
    form.order_by = menu.order_by;
}

function remove(menu) {
    if (confirm("Delete this menu?")) {
        form.delete(`/admin/menus/${menu.id}`);
    }
}

function fetchMenus() {
    router.get(
        "/admin/menus",
        { search: search.value },
        { preserveState: true, replace: true }
    );
}

function resetSearch() {
    search.value = "";
    router.get("/admin/menus", {}, { preserveState: false, replace: true });
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
        <Head title="Menu Page" />
        <FlashMessage
            v-if="$page.props.flash?.status"
            :message="$page.props.flash.status"
            type="success"
        />
        <Layout>
            <Breadcrumb />
            <div class="flex justify-between gap-3 font-robo">
                <!-- Menu List -->
                <div
                    class="bg-white shadow rounded p-6 w-2/3 h-fit border border-gray-300"
                >
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold w-1/3">All Menus</h2>
                        <div class="w-2/3">
                            <SearchFilter
                                v-model="search"
                                placeholder="Search menus..."
                                @search="fetchMenus"
                                @reset="resetSearch"
                            />
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table
                            class="min-w-full table-auto border border-gray-400 bg-white rounded shadow"
                        >
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left">#</th>
                                    <th class="px-4 py-2 text-left">Title</th>
                                    <th class="px-4 py-2 text-left">Parent</th>
                                    <th class="px-4 py-2 text-left">Route</th>
                                    <th class="px-4 py-2 text-left">
                                        Permissions
                                    </th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-left">Icon</th>
                                    <!-- <th class="px-4 py-2 text-left">Admin Left</th> -->
                                    <th class="px-4 py-2 text-left">Top</th>
                                    <th class="px-4 py-2 text-left">Left</th>
                                    <th class="px-4 py-2 text-left">Footer</th>
                                    <th class="px-4 py-2 text-left">Order</th>
                                    <th class="px-4 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(menu, i) in menuList.data"
                                    :key="menu.id"
                                    class="border-t border-gray-400 hover:bg-gray-50"
                                >
                                    <td class="px-4 py-2">{{ i + 1 }}</td>
                                    <td
                                        class="px-4 py-2"
                                        v-html="highlight(menu.title)"
                                    ></td>
                                    <td class="px-4 py-2">
                                        {{ menu.parent?.title ?? "—" }}
                                    </td>
                                    <td class="px-4 py-2">{{ menu.route }}</td>
                                    <td class="px-4 py-2">
                                        <div class="flex flex-wrap">
                                            <template
                                                v-if="
                                                    Array.isArray(
                                                        menu.permission_class
                                                    )
                                                "
                                            >
                                                <span
                                                    v-for="(
                                                        perm, index
                                                    ) in menu.permission_class"
                                                    :key="index"
                                                    class="badge-success px-2 py-1 rounded text-sm"
                                                >
                                                    {{ perm }}
                                                </span>
                                            </template>
                                            <span
                                                v-else
                                                class="badge-success px-2 py-1 rounded text-sm"
                                            >
                                                {{ menu.permission_class }}
                                            </span>
                                        </div>
                                    </td>
                                    <td
                                        class="px-4 py-2"
                                        v-html="
                                            menu.status
                                                ? `<i class='fa-solid fa-check text-green-500'></i>`
                                                : `<i class='fa-solid fa-xmark text-red-500'></i>`
                                        "
                                    ></td>
                                    <td class="px-4 py-2">
                                        <i :class="menu.icon"></i>
                                    </td>
                                    <!-- <td class="px-4 py-2" v-html="menu.admin_left_section ? 
                                        `<i class='fa-solid fa-check text-green-500'></i>` : 
                                        `<i class='fa-solid fa-xmark text-red-500'></i>`">
                                    </td> -->
                                    <td
                                        class="px-4 py-2"
                                        v-html="
                                            menu.top
                                                ? `<i class='fa-solid fa-check text-green-500'></i>`
                                                : `<i class='fa-solid fa-xmark text-red-500'></i>`
                                        "
                                    ></td>
                                    <td
                                        class="px-4 py-2"
                                        v-html="
                                            menu.left
                                                ? `<i class='fa-solid fa-check text-green-500'></i>`
                                                : `<i class='fa-solid fa-xmark text-red-500'></i>`
                                        "
                                    ></td>
                                    <td
                                        class="px-4 py-2"
                                        v-html="
                                            menu.footer
                                                ? `<i class='fa-solid fa-check text-green-500'></i>`
                                                : `<i class='fa-solid fa-xmark text-red-500'></i>`
                                        "
                                    ></td>
                                    <td class="px-4 py-2">
                                        {{ menu.order_by }}
                                    </td>
                                    <td class="px-4 py-2 space-x-2">
                                        <button
                                            @click="edit(menu)"
                                            class="text-blue-600 hover:underline"
                                        >
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button
                                            @click="remove(menu)"
                                            class="text-red-600 hover:underline"
                                        >
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <Pagination :links="menuList.links" />
                </div>

                <!-- Menu Form -->
                <div
                    class="bg-white shadow rounded p-6 w-1/3 h-fit border border-gray-300"
                >
                    <h2 class="text-lg font-semibold mb-4">
                        {{ editing ? "Edit" : "Create" }} Menu
                    </h2>
                    <form @submit.prevent="submit" class="space-y-4">
                        <TextInput
                            id="title"
                            label="Title"
                            v-model="form.title"
                            :error="form.errors.title"
                            placeholder="Title..."
                            @input="form.errors.title = null"
                        />
                        <TextInput
                            id="route"
                            label="Route/URL"
                            v-model="form.route"
                            :error="form.errors.route"
                            placeholder="Route/URL..."
                            @input="form.errors.route = null"
                        />

                        <SelectInput
                            v-model="form.parent_id"
                            :options="allMenus"
                            label="Parent Menu"
                            placeholder="Select Parent Menu..."
                            :error="form.errors.parent_id"
                        />

                        <!-- Multiple select -->
                        <SelectInput
                            v-model="form.permission_class"
                            :options="allPermissions"
                            label="Permissions"
                            multiple
                            placeholder="Select permissions..."
                            :error="form.errors.permission_class"
                        />

                        <!-- Toggles -->
                        <div class="grid grid-cols-2 gap-2">
                            <label>
                                <input type="checkbox" v-model="form.status" />
                                Status
                            </label>
                            <!-- <label>
                                <input
                                    type="checkbox"
                                    v-model="form.admin_left_section"
                                />
                                Admin Left Section
                            </label> -->
                            <label>
                                <input type="checkbox" v-model="form.top" />
                                Top
                            </label>
                            <label>
                                <input type="checkbox" v-model="form.left" />
                                Left
                            </label>
                            <label>
                                <input type="checkbox" v-model="form.footer" />
                                Footer
                            </label>
                        </div>

                        <IconPicker
                            v-model="form.icon"
                            label="Icon"
                            placeholder="Select an icon..."
                            :error="form.errors.icon"
                        />

                        <TextInput
                            id="order_by"
                            label="Order"
                            v-model="form.order_by"
                            :error="form.errors.order_by"
                            placeholder="Order number..."
                            @input="form.errors.order_by = null"
                        />

                        <button
                            type="submit"
                            class="btn-success"
                            :class="{ 'btn-spinner': form.processing }"
                            :disabled="form.processing"
                        >
                            <span>
                                {{
                                    form.processing
                                        ? "Processing..."
                                        : editing
                                        ? "Update"
                                        : "Create"
                                }}
                            </span>
                            <span v-if="form.processing" class="spinner"></span>
                        </button>
                    </form>
                </div>
            </div>
        </Layout>
    </div>
</template>
