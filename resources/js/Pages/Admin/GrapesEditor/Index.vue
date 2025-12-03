<script setup>
import Breadcrumb from "@/Shared/Breadcrumb.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";
import Pagination from "@/Shared/Pagination.vue";
import Layout from "@/Shared/Layout.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import SearchFilter from "@/Shared/SearchFilter.vue";
import { route } from "ziggy-js";
import { ref } from "vue";

const props = defineProps({
    pages: Object,
    search: String,
});

const search = ref(props.search || "");

function deletePage(page) {
    if (confirm("Are you sure you want to delete this page?")) {
        useForm({}).delete(route("pages.destroy", page.id), {
            preserveScroll: true,
        });
    }
}

function fetchPages() {
    router.get(
        route("pages.grapesjs"),
        { search: search.value },
        {
            preserveState: true,
            replace: true,
        }
    );
}

function resetSearch() {
    search.value = "";
    router.get(
        route("pages.grapesjs"),
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
    return text.replace(regex, '<span class="bg-yellow-500">$1</span>');
}
</script>

<template>
    <div>
        <Head title="Page Builder List" />
        <FlashMessage
            v-if="$page.props.flash?.status"
            :message="$page.props.flash.status"
            type="success"
        />
        <Layout>
            <Breadcrumb />

            <div
                class="bg-white shadow rounded p-6 w-full border border-gray-300"
            >
                <div class="flex justify-between items-center mb-4">
                    <div class="w-1/3">
                        <h2 class="text-lg font-bold">All Pages</h2>
                    </div>

                    <div class="w-1/3">
                        <SearchFilter
                            v-model="search"
                            placeholder="Search pages..."
                            @search="fetchPages"
                            @reset="resetSearch"
                        />
                    </div>

                    <div class="w-1/3 flex justify-end">
                        <Link :href="route('pages.editor')" class="btn rounded-sm fw-normal"
                            >Add New Page</Link
                        >
                    </div>
                </div>

                <table class="min-w-full table-auto border border-gray-400">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">#</th>
                            <th class="px-4 py-2 text-left">Title</th>
                            <th class="px-4 py-2 text-left">Slug</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(page, i) in pages.data"
                            :key="page.id"
                            class="border-t"
                        >
                            <td class="px-4 py-2">{{ i + 1 }}</td>
                            <td
                                class="px-4 py-2"
                                v-html="highlight(page.title)"
                            ></td>
                            <td class="px-4 py-2">{{ page.slug }}</td>
                            <td class="px-4 py-2">
                                <Link
                                    :href="`/${page.slug}`"
                                    target="_blank"
                                    class="text-gray-500 hover:underline"
                                    ><i class="fa fa-eye"></i></Link
                                >
                                <Link
                                    :href="route('pages.edit', page.id)"
                                    class="text-blue-600 mx-4 hover:underline"
                                >
                                    <i class="fa fa-edit"></i>
                                </Link>
                                <button
                                    @click="deletePage(page)"
                                    class="text-red-600 hover:underline"
                                >
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <Pagination :links="pages.links" />
            </div>
        </Layout>
    </div>
</template>
