<script setup>
import { Head, router } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";
import Layout from "@/Shared/Layout.vue";
import Breadcrumb from "@/Shared/Breadcrumb.vue";
import Pagination from "@/Shared/Pagination.vue";
import SearchFilter from "@/Shared/SearchFilter.vue";
import { route } from "ziggy-js";

const props = defineProps({
    billingItems: Object,
    search: String,
    date_from: String,
    date_to: String,
});

const search = ref(props.search || "");
const dateFrom = ref(props.date_from || "");
const dateTo = ref(props.date_to || "");

function fetchItems() {
    router.get(
        route("admin.billing.item"),
        {
            search: search.value,
            date_from: dateFrom.value,
            date_to: dateTo.value,
        },
        { preserveState: true, replace: true }
    );
}

function resetFilters() {
    search.value = "";
    dateFrom.value = "";
    dateTo.value = "";

    router.get(route("admin.billing.item"), {}, { preserveState: false });
}

function highlight(text) {
    if (!text || !search.value) return text;

    const escaped = search.value.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
    const regex = new RegExp(`(${escaped})`, "gi");

    return text.replace(regex, `<span class='bg-yellow-300'>$1</span>`);
}

const animatedPlaceholder = ref("");
const placeholderText = "Search Invoice, Item Code, IMEI, Item Name...";
let intervalId;

onMounted(() => {
    let i = 0;
    let forward = true;

    intervalId = setInterval(() => {
        if (forward) {
            animatedPlaceholder.value = placeholderText.slice(0, i + 1);
            i++;
            if (i === placeholderText.length) forward = false;
        } else {
            animatedPlaceholder.value = placeholderText.slice(0, i);
            i--;
            if (i === 0) forward = true;
        }
    }, 120);
});

onUnmounted(() => clearInterval(intervalId));
</script>

<template>
    <Layout>
        <Head title="Billing Item Search" />
        <Breadcrumb />

        <div class="bg-white shadow rounded p-6 border border-gray-300">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-bold">
                    <i class="fa-solid fa-list"></i>
                    {{ billingItems.total }} Records Found
                </h2>
                <div class="flex w-1/3">
                    <SearchFilter
                        v-model="search"
                        :placeholder="animatedPlaceholder"
                        @search="fetchItems"
                        @reset="resetFilters"
                    />
                </div>

                <div class="flex gap-2 items-center">
                    <input
                        type="date"
                        v-model="dateFrom"
                        @change="fetchItems"
                        class="border border-gray-300 p-2 rounded"
                    />
                    <i
                        class="fa-solid fa-circle-exclamation text-indigo-600"
                        title="First Input Date From and Second Input Date to."
                    ></i>
                    <input
                        type="date"
                        v-model="dateTo"
                        @change="fetchItems"
                        class="border border-gray-300 p-2 rounded"
                    />
                </div>
            </div>

            <div
                v-if="!search && !dateFrom && !dateTo"
                class="text-center py-5 w-full bg-green-100 text-gray-500 font-bold rounded-sm"
            >
                🔍 Enter search text or date filter to view items.
            </div>

            <div
                v-else-if="billingItems.data.length === 0"
                class="text-center py-5 bg-yellow-100 w-full rounded-sm text-red-500 font-bold"
            >
                ❌ No records found.
            </div>

            <table v-else class="min-w-full table-auto border border-gray-400">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Invoice</th>
                        <th class="px-4 py-2">Item Code</th>
                        <th class="px-4 py-2">Item Name</th>
                        <th class="px-4 py-2">Warranty</th>
                        <th class="px-4 py-2">IMEI/SL</th>
                        <th class="px-4 py-2">Note</th>
                        <th class="px-4 py-2">Qty</th>
                        <th class="px-4 py-2">Price</th>
                        <th class="px-4 py-2">Tax</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(item, i) in billingItems.data" :key="item.id">
                        <td>{{ i + 1 }}</td>

                        <td v-html="highlight(item.billing?.invoice)"></td>
                        <td v-html="highlight(item.item_code)"></td>
                        <td v-html="highlight(item.item_name)"></td>

                        <td v-html="highlight(item.warranty_date)"></td>
                        <td>{{ item.imei_sl }}</td>
                        <td>{{ item.note }}</td>
                        <td>{{ item.qty }}</td>
                        <td>{{ item.price }}</td>
                        <td>{{ item.tax }}</td>
                    </tr>
                </tbody>
            </table>

            <Pagination :links="billingItems.links" />
        </div>
    </Layout>
</template>
