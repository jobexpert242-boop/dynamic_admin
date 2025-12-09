<script setup>
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    invoice: Object,
    transactions: Array,
});

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
</script>

<template>
    <Head title="View Billing" />

    <div
        class="border rounded border-gray-300 shadow-sm p-4 bg-white w-4xl m-auto"
    >
        <div class="mt-4">
            <div class="flex justify-between items-center mb-2 border-b border-b-gray-300 pb-2">
                <div>
                    <div class="flex gap-3 items-center">
                        <h2 class="text-lg font-semibold">Invoice</h2>
                        <h1 class="text-xl font-bold">
                            # {{ invoice.invoice }}
                        </h1>
                    </div>
                </div>
                <div class="text-end">
                    <div class="flex justify-end">
                        <img
                            src="https://support.comitsbd.com/public//images/logo_1755536796.jpg"
                            class="h-10"
                            alt="site logo"
                        />
                    </div>
                    <address class="flex flex-col gap-1.5 mt-1">
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

            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-semibold mb-1.5 text-sm">Invoice To.</h2>
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
                <table class=" w-md">
                    <tbody>
                        <tr class="text-lg">
                            <td class="p-1.5">Invoice</td>
                            <td class="p-1.5 text-end">
                                # {{ invoice.invoice }}
                            </td>
                        </tr>
                        <tr>
                            <td class="p-1.5">Status</td>
                            <td class="p-1.5 text-end">
                                <span
                                    class="p-0.5 rounded text-white text-xs"
                                    :class="statusColor(invoice.status)"
                                >
                                    {{ invoice.status }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-1.5">Invoice Date</td>
                            <td class="p-1.5 text-end">{{ formatDate(invoice.invoice_date) }}</td>
                        </tr>
                        <tr>
                            <td class="p-1.5">Due Date</td>
                            <td class="p-1.5 text-end">{{ formatDate(invoice.updated_at) }}</td>
                        </tr>
                        <tr>
                            <td class="p-1.5">Amount Due</td>
                            <td class="p-1.5 text-end">{{ $page.props.site?.currency }} {{ formatCurrency(invoice.sub_total - invoice.paid) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <h2 class="font-semibold mb-2">Invoice Items</h2>
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-1.5">#</th>
                        <th class="p-1.5">Item Name</th>
                        <th class="p-1.5">Price</th>
                        <th class="p-1.5">Qty</th>
                        <th class="p-1.5">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, $i) in invoice.items" :key="item.id">
                        <td class="p-1.5">
                            {{ $i + 1 }}
                        </td>
                        <td class="p-1.5 text-center">
                            {{ item.item_name }}
                        </td>
                        <td class="p-1.5 text-center">
                        {{ $page.props.site?.currency }}
                            {{ formatCurrency(item.price) }}
                        </td>
                        <td class="p-1.5 text-center">
                            {{ item.qty }}
                        </td>
                        <td class="p-1.5 text-end">
                        {{ $page.props.site?.currency }}
                            {{ formatCurrency(item.price * item.qty) }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="flex justify-end">
                <tbody>
                    <tr>
                        <td class="p-1.5 w-60">Subtotal</td>
                        <td class="p-1.5 w-60 text-end">
                            {{ $page.props.site?.currency }}
                            {{ formatCurrency(invoice.sub_total) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="p-1.5">Discount</td>
                        <td class="p-1.5 text-end">
                            {{ $page.props.site?.currency }}
                            {{ formatCurrency(invoice.total_discount) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="p-1.5">Tax</td>
                        <td class="p-1.5 text-end">
                            {{ $page.props.site?.currency }}
                            {{ formatCurrency(invoice.total_tax) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="p-1.5">Total</td>
                        <td class="p-1.5 text-end">
                            {{ $page.props.site?.currency }}
                            {{ formatCurrency(invoice.total) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="p-1.5">Round Value</td>
                        <td class="p-1.5 text-end">
                            {{ $page.props.site?.currency }}
                            {{ formatCurrency(invoice.round_value) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="p-1.5">Total Paid</td>
                        <td class="p-1.5 text-end">
                            {{ $page.props.site?.currency }}
                            {{ formatCurrency(invoice.paid) }}
                        </td>
                    </tr>
                    <tr class="text-2xl">
                        <td class="p-1.5">Amount Due</td>
                        <td class="p-1.5 text-end">
                            {{ $page.props.site?.currency }}
                            {{
                                formatCurrency(invoice.sub_total - invoice.paid)
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
                        <th class="px-3 py-2">Status</th>
                        <th class="px-3 py-2">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(t, index) in props.transactions"
                        :key="t.id"
                        class="border-b"
                    >
                        <td class="px-3 py-2">{{ index + 1 }}</td>
                        <td class="px-3 py-2">
                            {{ t.payee ?? "Customer" }}
                        </td>
                        <td class="px-3 py-2">
                            {{ new Date(t.created_at).toLocaleDateString() }}
                        </td>
                        <td class="px-3 py-2">
                            {{ t.invoice_id }}
                        </td>
                        <td class="px-3 py-2 capitalize">
                            {{ t.payment_method }}
                        </td>
                        <td class="px-3 py-2">
                            <span class="p-1 bg-green-300 rounded">{{ t.status }}</span>
                        </td>
                        <td class="px-3 py-2 font-semibold text-green-600 text-end">
                           {{ $page.props.site?.currency }} {{ t.amount }}
                        </td>
                    </tr>

                    <tr v-if="props.transactions.length === 0">
                        <td colspan="7" class="py-3 text-red-500">
                            <p class="text-center">No transactions found.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="border rounded-sm bg-gray-100 mt-5 border-gray-300 p-3">
            <p class="mb-2 text-xl">Invoice Termes & conditions</p>
            <p v-html="invoice.terms"></p>
        </div>
    </div>
</template>
