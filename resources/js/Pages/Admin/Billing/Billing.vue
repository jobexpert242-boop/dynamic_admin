<script setup>
import { Head, router, Link } from "@inertiajs/vue3";
import { onMounted, onUnmounted, ref } from "vue";
import Layout from "@/Shared/Layout.vue";
import Breadcrumb from "@/Shared/Breadcrumb.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";
import Pagination from "@/Shared/Pagination.vue";
import SearchFilter from "@/Shared/SearchFilter.vue";
import { route } from "ziggy-js";

const props = defineProps({
    invoices: Object,
    search: String,
});

const search = ref(props.search || "");

function deleteInvoice(id) {
    if (confirm("Are you sure you want to delete this invoice?")) {
        router.delete(route("admin.invoice.delete", id));
    }
}

function fetchInvoices() {
    router.get(
        route("admin.billing"),
        { search: search.value },
        { preserveState: true, replace: true }
    );
}

function resetSearch() {
    search.value = "";
    router.get(
        route("admin.billing"),
        {},
        { preserveState: false, replace: true }
    );
}

// Highlight search keywords
function highlight(text) {
    const keyword = search.value.trim();
    if (!keyword) return text;

    const escapedKeyword = keyword.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
    const regex = new RegExp(`(${escapedKeyword})`, "gi");

    return text.replace(regex, '<span class="bg-yellow-300">$1</span>');
}

// search place holder type
const animatedPlaceholder = ref("");

const placeholderText = "Search by invoice, customer, or item...";
let intervalId;

onMounted(() => {
    let i = 0;
    let typingForward = true;

    intervalId = setInterval(() => {
        if (typingForward) {
            animatedPlaceholder.value = placeholderText.slice(0, i + 1);
            i++;
            if (i === placeholderText.length) {
                typingForward = false;
            }
        } else {
            animatedPlaceholder.value = placeholderText.slice(0, i);
            i--;
            if (i === 0) {
                typingForward = true;
            }
        }
    }, 200);
});

onUnmounted(() => {
    clearInterval(intervalId);
});

// Modal visibility
const showInvoiceModal = ref(false);

const selectedInvoice = ref(null);

function openInvoiceModal(invoice) {
    selectedInvoice.value = invoice;
    showInvoiceModal.value = true;
}

function closeInvoiceModal() {
    selectedInvoice.value = null;
    showInvoiceModal.value = false;
}
function overlayClick(e) {
    if (e.target.id === "invoice-modal-overlay") {
        closeInvoiceModal();
    }
}
</script>

<template>
    <Layout>
        <Head title="Billing List" />
        <Breadcrumb />
        <FlashMessage
            v-if="$page.props.flash?.status"
            :message="$page.props.flash.status"
            type="success"
        />

        <div class="bg-white shadow rounded p-6 border border-gray-300">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold"><i class="fa-solid fa-list"></i> Billing List</h2>
                <div class="flex gap-2 w-1/2">
                    <SearchFilter
                        v-model="search"
                        :placeholder="animatedPlaceholder"
                        @search="fetchInvoices"
                        @reset="resetSearch"
                    />
                </div>
                <div class="text-end">
                    <Link
                        :href="route('admin.createInvoice')"
                        class="btn rounded-sm"
                        ><i class="fa fa-plus"></i> Add Invoice</Link
                    >
                </div>
            </div>

            <table class="min-w-full table-auto border border-gray-400">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">#</th>
                        <th class="px-4 py-2 text-left">Invoice</th>
                        <th class="px-4 py-2 text-left">Customer</th>
                        <th class="px-4 py-2 text-left">Items</th>
                        <th class="px-4 py-2 text-left">Total</th>
                        <th class="px-4 py-2 text-left">Paid</th>
                        <th class="px-4 py-2 text-left">Due</th>
                        <th class="px-4 py-2 text-left">Discount</th>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(invoice, index) in invoices.data"
                        :key="invoice.id"
                    >
                        <td class="px-4 py-2">
                            {{ index + 1 }}
                            <button
                                type="button"
                                class="text-gray-400 ml-2"
                                @click="openInvoiceModal(invoice)"
                            >
                                <i class="fa fa-bars"></i>
                            </button>
                        </td>
                        <td
                            class="px-4 py-2"
                            v-html="highlight(invoice.invoice)"
                        ></td>
                        <td
                            class="px-4 py-2"
                            v-html="highlight(invoice.customer.name)"
                        ></td>
                        <td class="px-4 py-2">
                            <ul>
                                <li
                                    v-for="item in invoice.items"
                                    :key="item.id"
                                    v-html="highlight(item.item_name)"
                                ></li>
                            </ul>
                        </td>
                        <td class="px-4 py-2">{{ invoice.total }}</td>
                        <td class="px-4 py-2">{{ invoice.paid ?? 0 }}</td>
                        <td class="px-4 py-2">
                            {{ invoice.total - (invoice.paid ?? 0) }}
                        </td>
                        <td class="px-4 py-2">{{ invoice.total_discount }}</td>
                        <td class="px-4 py-2">{{ invoice.invoice_date }}</td>
                        <td class="px-4 py-2 capitalize">
                            <span class="badge-success">{{
                                invoice.status
                            }}</span>
                        </td>
                        <td class="px-4 py-2 gap-2">
                            <Link
                                :href="
                                    route('admin.invoice.view', invoice.invoice)
                                "
                                class="bg-slate-500 p-1.5 rounded-sm text-white"
                                ><i class="fa fa-eye"></i
                            ></Link>
                            <Link
                                :href="route('admin.invoice.edit', invoice.invoice)"
                                class="bg-indigo-500 p-1.5 text-white rounded-sm mx-2"
                                ><i class="fa fa-edit"></i
                            ></Link>
                            <button
                                @click="deleteInvoice(invoice.id)"
                                class="p-1.5 rounded-sm bg-red-500 text-white"
                            >
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <Pagination :links="invoices.links" />
        </div>
    </Layout>

    <div
        v-if="showInvoiceModal"
        class="fixed inset-0 bg-black/50 flex justify-center items-center z-50"  id="invoice-modal-overlay" @click="overlayClick"
    >
        <div
            class="bg-white rounded shadow-lg w-3/4 max-h-[80vh] overflow-y-auto p-5 relative"
        >
            <button
                class="absolute top-3 right-3 text-gray-500"
                @click="closeInvoiceModal"
            >
                <i class="fa fa-times"></i>
            </button>

            <h2 class="text-xl font-bold mb-3">Invoice Details</h2>

            <div v-if="selectedInvoice">
                <div class="flex flex-wrap justify-between gap-5 mb-3">
                    <p>
                        <strong>Invoice:</strong> {{ selectedInvoice.invoice }}
                    </p>
                    <p>
                        <strong>Customer:</strong>
                        {{ selectedInvoice.customer.name }}
                    </p>
                    <p><strong>Status:</strong> {{ selectedInvoice.status }}</p>
                    <p>
                        <strong>Invoice Date:</strong>
                        {{ selectedInvoice.invoice_date }}
                    </p>
                    <p><strong>Paid:</strong> {{ selectedInvoice.paid }}</p>
                    <p>
                        <strong>Sub Total:</strong>
                        {{ selectedInvoice.sub_total }}
                    </p>
                    <p>
                        <strong>Total Tax:</strong>
                        {{ selectedInvoice.total_tax }}
                    </p>
                    <p>
                        <strong>Total Discount:</strong>
                        {{ selectedInvoice.total_discount }}
                    </p>
                    <p>
                        <strong>Round Off:</strong>
                        {{ selectedInvoice.round_value }}
                    </p>
                    <p><strong>Total:</strong> {{ selectedInvoice.total }}</p>
                </div>
                <p v-html="selectedInvoice.terms"></p>

                <h3 class="mt-4 font-semibold">Items</h3>
                <table class="min-w-full border mt-2">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-3 py-2 border">Item Code</th>
                            <th class="px-3 py-2 border">Item Name</th>
                            <th class="px-3 py-2 border">Qty</th>
                            <th class="px-3 py-2 border">Price</th>
                            <th class="px-3 py-2 border">Tax</th>
                            <th class="px-3 py-2 border">Warranty</th>
                            <th class="px-3 py-2 border">IMEI/SL</th>
                            <th class="px-3 py-2 border">Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in selectedInvoice.items"
                            :key="item.id"
                        >
                            <td class="px-3 py-1 border">
                                {{ item.item_code || "-" }}
                            </td>
                            <td class="px-3 py-1 border">
                                {{ item.item_name }}
                            </td>
                            <td class="px-3 py-1 border">{{ item.qty }}</td>
                            <td class="px-3 py-1 border">{{ item.price }}</td>
                            <td class="px-3 py-1 border">{{ item.tax }}%</td>
                            <td class="px-3 py-1 border">
                                {{ item.warranty_date || "-" }}
                            </td>
                            <td class="px-3 py-1 border">
                                {{ item.imei_sl || "-" }}
                            </td>
                            <td class="px-3 py-1 border">
                                {{ item.note || "-" }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
