<script setup>
import { useForm, Head, router, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import TextInput from "@/Shared/TextInput.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";
import Layout from "@/Shared/Layout.vue";
import { route } from "ziggy-js";
import Breadcrumb from "@/Shared/Breadcrumb.vue";

const form = useForm({
    customer_id: "",
    address: "",
    invoice: "",
    invoice_date: "",
    sale_tax: 0,
    total: 0,
    discount: 0,
    discount_type: "",
    status: "due",
    terms: "",
    items: [
        { item_code: "", item_name: "", qty: 1, price: 0, tax: 0, note: "" },
    ],
});

function addItem() {
    form.items.push({
        item_code: "",
        item_name: "",
        qty: 1,
        price: 0,
        tax: 0,
        note: "",
    });
}

function removeItem(index) {
    form.items.splice(index, 1);
}

function submit() {
    form.post(route("admin.invoice.store"), {
        onSuccess: () => {
            form.reset();
        },
    });
}
</script>

<template>
    <Layout>
        <Head title="Create Billing" />
        <Breadcrumb />
        <FlashMessage
            v-if="$page.props.flash?.status"
            :message="$page.props.flash.status"
            type="success"
        />

        <div class="border border-gray-300 p-2 shadow-sm rounded">
            <div class="flex justify-between items-center border-b mb-4 pb-3">
                <h2 class="text-lg font-bold">Add Billing</h2>
                <Link :href="route('admin.billing')" class="btn rounded-sm"
                    >Invoice List</Link
                >
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <!-- Items -->
                <div>
                    <h3 class="font-semibold mb-2">Items</h3>
                    <div
                        v-for="(item, index) in form.items"
                        :key="index"
                        class="grid grid-cols-5 gap-2 mb-2"
                    >
                        <TextInput
                            label="Item Code"
                            v-model="item.item_code"
                            :error="form.errors[`items.${index}.item_code`]"
                        />
                        <TextInput
                            label="Item Name"
                            v-model="item.item_name"
                            :error="form.errors[`items.${index}.item_name`]"
                        />
                        <TextInput
                            label="Qty"
                            type="number"
                            v-model="item.qty"
                            :error="form.errors[`items.${index}.qty`]"
                        />
                        <TextInput
                            label="Price"
                            type="number"
                            v-model="item.price"
                            :error="form.errors[`items.${index}.price`]"
                        />
                        <TextInput
                            label="Tax"
                            type="number"
                            v-model="item.tax"
                        />
                        <button
                            type="button"
                            @click="removeItem(index)"
                            class="text-red-500"
                        >
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <button
                        type="button"
                        @click="addItem"
                        class="btn rounded-sm"
                    >
                        + Add Item
                    </button>
                </div>

                <TextInput
                    id="customer_id"
                    label="Customer ID"
                    v-model="form.customer_id"
                    :error="form.errors.customer_id"
                />
                <TextInput
                    id="invoice"
                    label="Invoice No"
                    v-model="form.invoice"
                    :error="form.errors.invoice"
                />
                <TextInput
                    id="invoice_date"
                    label="Invoice Date"
                    type="date"
                    v-model="form.invoice_date"
                    :error="form.errors.invoice_date"
                />

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="btn rounded-sm"
                        :disabled="form.processing"
                    >
                        {{
                            form.processing ? "Save Billing..." : "Save Billing"
                        }}
                    </button>
                </div>
            </form>
        </div>
    </Layout>
</template>
