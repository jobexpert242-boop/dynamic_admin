<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
import Breadcrumb from "@/Shared/Breadcrumb.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";
import { route } from "ziggy-js";
import { reactive, ref } from "vue";

const props = defineProps({
    invoice: Object,
    public_url: String,
});

const form = reactive({
    status: props.invoice.status,
});

function updateStatus() {
    router.put(route("invoice.update.status", props.invoice.invoice), {
        status: form.status,
    });
}

// Copy function
const copyUrl = () => {
    if (!navigator || !navigator.clipboard) {
        alert("Clipboard not supported in this browser.");
        return;
    }

    navigator.clipboard
        .writeText(props.public_url)
        .then(() => {
            alert("URL copied to clipboard!");
        })
        .catch(() => {
            alert("Failed to copy URL.");
        });
};
const isOpen = ref(false);

function toggleDropdown() {
    isOpen.value = !isOpen.value;
}
function statusColor(status) {
    switch (status) {
        case "paid":
            return "bg-green-500";
        case "due":
            return "bg-yellow-500";
        case "unpaid":
            return "bg-red-500";
        case "cancelled":
            return "bg-gray-500";
        default:
            return "bg-neutral-400";
    }
}
function formatDate(dateString) {
    if (!dateString) return "";
    const date = new Date(dateString);
    return new Intl.DateTimeFormat("en-GB", {
        day: "2-digit",
        month: "short",
        year: "numeric",
    }).format(date);
}
function formatCurrency(value) {
    return Number(value).toFixed(2);
}
function newTab(e) {
  e.preventDefault();
  window.open(props.public_url, "_blank");
}
</script>

<template>
    <Layout>
        <Head title="View Billing" />
        <Breadcrumb />
        <FlashMessage
            v-if="$page.props.flash?.status"
            :message="$page.props.flash.status"
            type="success"
        />
        <div class="border rounded border-gray-300 shadow-sm p-5 mb-3 bg-white">
            <div class="relative group w-full">
                <label class="form-label">Public Invoice URL</label>
                <div class="relative w-full">
                    <input
                        type="text"
                        :value="public_url"
                        readonly
                        class="border p-2 rounded-sm border-gray-300 focus:outline-0 w-full pr-10"
                    />
                    <i
                        class="fa fa-copy absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer opacity-0 group-hover:opacity-100 transition"
                        @click="copyUrl"
                    ></i>
                </div>
                <small class="text-green-500">
                    Anyone can view this invoice using this link.
                </small>
            </div>
        </div>

        <div class="border rounded border-gray-300 shadow-sm p-5 bg-white">
            <div
                class="flex justify-between items-center mb-4 border-b-2 pb-3 border-dashed"
            >
                <h1 class="text-xl font-bold">
                    Invoice: {{ invoice.invoice }}
                </h1>
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        class="bg-sky-500 text-white py-1.5 px-2 rounded-sm hover:bg-sky-600 text-xs"
                    >
                        <i class="fa-regular fa-envelope"></i> Send Email
                    </button>

                    <select
                        v-model="form.status"
                        @change="updateStatus"
                        class="bg-teal-500 p-1.5 rounded-sm focus:outline-0 text-white border-0 cursor-pointer"
                    >
                        <option value="due">Due</option>
                        <option value="unpaid">Unpaid</option>
                        <option value="paid">Paid</option>
                        <option value="cancelled">Cancelled</option>
                    </select>

                    <button
                        type="button"
                        @click="newTab"
                        class="bg-amber-500 text-white py-1.5 px-2 rounded-sm hover:bg-amber-600 text-xs"
                    >
                        <i class="fa-regular fa-eye"></i> Preview
                    </button>                    

                    <Link
                        :href="route('admin.invoice.edit', invoice.invoice)"
                        class="bg-purple-500 text-white py-1.5 px-2 rounded-sm hover:bg-purple-600 text-xs"
                    >
                        <i class="fa fa-edit"></i> Edit
                    </Link>

                    <div class="relative inline-block text-left">
                        <button
                            @click="toggleDropdown"
                            class="bg-green-500 text-white py-1.5 px-2 rounded-sm hover:bg-green-600 text-xs flex items-center"
                            type="button"
                        >
                            <i class="fa-regular fa-file-pdf"></i>
                            PDF
                            <i class="fa-solid fa-angle-down ml-1"></i>
                        </button>
                        <div
                            v-show="isOpen"
                            class="absolute mt-2 z-10 bg-green-600 border rounded shadow-lg w-44"
                        >
                            <ul class="text-sm font-medium py-2">
                                <li>
                                    <a
                                        href="#"
                                        class="inline-flex items-center w-full p-2 hover:bg-green-700 text-white"
                                        >View Pdf</a
                                    >
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        class="inline-flex items-center w-full p-2 hover:bg-green-700 text-white"
                                        >Download Pdf</a
                                    >
                                </li>
                            </ul>
                        </div>
                    </div>

                    <button
                        type="button"
                        class="bg-indigo-500 text-white py-1.5 px-2 rounded-sm hover:bg-indigo-600 text-xs"
                    >
                        <i class="fa-solid fa-print"></i> Print
                    </button>

                    <button
                        type="button"
                        class="bg-slate-500 text-white py-1.5 px-2 rounded-sm hover:bg-slate-600 text-xs"
                    >
                        <i class="fa fa-plus"></i> Add Payment
                    </button>

                    <Link
                        :href="route('admin.billing')"
                        class="bg-blue-500 text-white py-1.5 px-2 rounded-sm hover:bg-blue-600 text-xs"
                    >
                        <i class="fa-solid fa-list"></i> Back to List
                    </Link>
                </div>
            </div>

            <div class="mt-10 px-10">
                <div class="flex justify-between mb-6">
                    <div>
                        <h1 class="text-xl font-bold">Invoice</h1>
                        <h2 class="text-md mt-2 mb-5">
                            # {{ invoice.invoice }}
                        </h2>
                        <span
                            class="p-2 rounded text-white"
                            :class="statusColor(invoice.status)"
                        >
                            {{ invoice.status }}
                        </span>
                    </div>
                    <div class="text-end">
                        <p class="text-md font-semibold">Company Location:</p>
                        <address class="flex flex-col gap-2 mt-3">
                            <span v-if="$page.props.site?.company_location">{{
                                $page.props.site?.company_location
                            }}</span>
                            <span v-if="$page.props.site?.company_contact">
                                Contact No. :
                                <a
                                    :href="`tel:${$page.props.site?.company_contact}`"
                                    class="text-blue-600 hover:underline"
                                >
                                    {{ $page.props.site?.company_contact }}
                                </a>
                            </span>
                            <span v-if="$page.props.site?.company_email">
                                Email :
                                <a
                                    :href="`mailto:${$page.props.site?.company_email}`"
                                    class="text-blue-600 hover:underline"
                                >
                                    {{ $page.props.site?.company_email }}
                                </a>
                            </span>
                            <span v-if="$page.props.site?.company_web">
                                Web :
                                <a
                                    :href="$page.props.site?.company_web"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="text-blue-600 hover:underline"
                                >
                                    {{ $page.props.site?.company_web }}
                                </a>
                            </span>
                        </address>
                    </div>
                </div>

                <div class="flex justify-between mb-3">
                    <div>
                        <h2 class="font-semibold mb-2 text-sm">Invoice To.</h2>
                        <div class="flex flex-col gap-1.5">
                            <p>
                                <strong>Name:</strong>
                                {{ invoice.customer.name || "" }}
                            </p>
                            <p>
                                <strong>Address:</strong>
                                {{ invoice.address || "" }}
                            </p>
                            <p><strong>Phone:</strong></p>
                            <p>
                                <strong>Email:</strong>
                                {{ invoice.customer.email || "" }}
                            </p>
                        </div>
                    </div>
                    <div class="text-end flex flex-col gap-1.5">
                        <div class="flex justify-end">
                            <img
                                src="https://support.comitsbd.com/public//images/logo_1755536796.jpg"
                                class="h-10"
                                alt="site logo"
                            />
                        </div>
                        <p class="text-sm">
                            <strong>Invoice Date : </strong>
                            {{ formatDate(invoice.invoice_date) }}
                        </p>
                        <p class="text-sm">
                            <strong>Due Date : </strong>
                            {{ formatDate(invoice.updated_at) }}
                        </p>
                        <h2>
                            <strong class="text-2xl">Invoice Total : </strong
                            ><span class="text-lg"
                                >{{ $page.props.site?.currency }}
                                {{ formatCurrency(invoice.sub_total) }}</span
                            >
                        </h2>
                        <h2>
                            <strong class="text-2xl">Total Paid : </strong
                            ><span class="text-lg"
                                >{{ $page.props.site?.currency }}
                                {{ formatCurrency(invoice.paid) }}</span
                            >
                        </h2>
                        <h2>
                            <strong class="text-2xl">Total Due : </strong
                            ><span class="text-lg"
                                >{{ $page.props.site?.currency }}
                                {{
                                    formatCurrency(
                                        invoice.sub_total - invoice.paid
                                    )
                                }}</span
                            >
                        </h2>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="font-semibold mb-2">Invoice Items</h2>
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-3 py-2">#</th>
                            <th class="px-3 py-2">Item Name</th>
                            <th class="px-3 py-2">Price</th>
                            <th class="px-3 py-2">Qty</th>
                            <th class="px-3 py-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, $i) in invoice.items" :key="item.id">
                            <td class="px-3 py-1">
                                {{ $i + 1 }}
                            </td>
                            <td class="px-3 py-2 text-center">
                                {{ item.item_name }}
                            </td>
                            <td class="px-3 py-2 text-center">
                                {{ formatCurrency(item.price) }}
                            </td>
                            <td class="px-3 py-2 text-center">
                                {{ item.qty }}
                            </td>
                            <td class="px-3 py-2 text-center">
                                {{ formatCurrency(item.price * item.qty) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="flex justify-end">
                    <tbody>
                        <tr>
                            <td class="w-60 px-3 py-3">Subtotal</td>
                            <td class="w-60 py-3 px-3">
                                {{ $page.props.site?.currency }}
                                {{ formatCurrency(invoice.sub_total) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="w-40 py-3 px-3">Discount</td>
                            <td class="w-40 py-3 px-3">
                                {{ $page.props.site?.currency }}
                                {{ formatCurrency(invoice.total_discount) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="w-40 py-3 px-3">Tax</td>
                            <td class="w-40 py-3 px-3">
                                {{ $page.props.site?.currency }}
                                {{ formatCurrency(invoice.total_tax) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="w-40 py-3 px-3">Total</td>
                            <td class="w-40 py-3 px-3">
                                {{ $page.props.site?.currency }}
                                {{ formatCurrency(invoice.total) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="w-40 py-3 px-3">Round Value</td>
                            <td class="w-40 py-3 px-3">
                                {{ $page.props.site?.currency }}
                                {{ formatCurrency(invoice.round_value) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="w-40 py-3 px-3">Total Paid</td>
                            <td class="w-40 py-3 px-3">
                                {{ $page.props.site?.currency }}
                                {{ formatCurrency(invoice.paid) }}
                            </td>
                        </tr>
                        <tr class="text-2xl">
                            <td class="w-40 py-3 px-3">Amount Due</td>
                            <td class="w-40 py-3 px-3">
                                {{ $page.props.site?.currency }}
                                {{
                                    formatCurrency(
                                        invoice.sub_total - invoice.paid
                                    )
                                }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div>
                <h2 class="font-semibold mb-2">Related Transtiction</h2>
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-3 py-2">#</th>
                            <th class="px-3 py-2">Name</th>
                            <th class="px-3 py-2">Date</th>
                            <th class="px-3 py-2">Invoice</th>
                            <th class="px-3 py-2">Account</th>
                            <th class="px-3 py-2">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="border rounded-sm bg-gray-100 mt-5 border-gray-300 p-3">
                <p class="mb-2 text-xl">Invoice Termes & conditions</p>
                <p v-html="invoice.terms"></p>
            </div>
        </div>
    </Layout>
</template>

<style scoped>
td,
th {
    text-align: start;
}
</style>
