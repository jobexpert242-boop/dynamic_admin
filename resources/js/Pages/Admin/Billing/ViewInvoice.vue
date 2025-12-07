<script setup>
import { Head, Link } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
import Breadcrumb from "@/Shared/Breadcrumb.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";

const props = defineProps({
    invoice: Object,
    public_url: String,
});
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
        <div class="border rounded border-gray-300 shadow-sm p-5">
            <div class="mb-3 relative group w-full">
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

            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">
                    Invoice: {{ invoice.invoice }}
                </h1>
                <Link
                    href="/admin/billing"
                    class="btn rounded-sm bg-gray-500 text-white"
                >
                    Back to List
                </Link>
            </div>

            <div class="border p-4 rounded mb-4">
                <h2 class="font-semibold mb-2">Customer Details</h2>
                <p><strong>Name:</strong> {{ invoice.customer.name }}</p>
                <p><strong>Address:</strong> {{ invoice.address }}</p>
                <p><strong>Status:</strong> {{ invoice.status }}</p>
                <p><strong>Invoice Date:</strong> {{ invoice.invoice_date }}</p>
                <p><strong>Paid:</strong> {{ invoice.paid }}</p>
                <p><strong>Sub Total:</strong> {{ invoice.sub_total }}</p>
                <p><strong>Total Tax:</strong> {{ invoice.total_tax }}</p>
                <p>
                    <strong>Total Discount:</strong>
                    {{ invoice.total_discount }}
                </p>
                <p><strong>Round Off:</strong> {{ invoice.round_value }}</p>
                <p><strong>Total:</strong> {{ invoice.total }}</p>
                <p><strong>Terms:</strong> {{ invoice.terms }}</p>
            </div>

            <div class="border p-4 rounded">
                <h2 class="font-semibold mb-2">Invoice Items</h2>
                <table class="min-w-full border">
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
                        <tr v-for="item in invoice.items" :key="item.id">
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
    </Layout>
</template>
