<script setup>
import { useForm, Head, Link, usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import TextInput from "@/Shared/TextInput.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";
import Layout from "@/Shared/Layout.vue";
import { route } from "ziggy-js";
import Breadcrumb from "@/Shared/Breadcrumb.vue";
import RichTextEditor from "@/Shared/RichTextEditor.vue";
import SelectInput from "@/Shared/SelectInput.vue";

const props = defineProps({
    invoice: Object,
    users: Array,
});

const page = usePage();
const roundOff = ref(false);

const form = useForm({
    customer_id: props.invoice.customer_id,
    // address: props.invoice.customer?.address || "",
    invoice: props.invoice.invoice,
    invoice_date: props.invoice.invoice_date,
    paid: props.invoice.paid,
    status: props.invoice.status,
    total: props.invoice.total,
    sub_total: props.invoice.sub_total,
    total_discount: props.invoice.total_discount,
    total_tax: props.invoice.total_tax,
    round_value: props.invoice.round_value,
    discount: props.invoice.discount,
    discount_type: props.invoice.discount_type,
    terms: props.invoice.terms || page.props.site?.inv_termes || "",
    items: props.invoice.items.map((item) => ({
        item_code: item.item_code,
        item_name: item.item_name,
        qty: item.qty,
        price: item.price,
        tax: item.tax,
        imei_sl: item.imei_sl,
        note: item.note,
        warranty_date: item.warranty_date,
    })),
    action: "",
});

const subTotal = computed(() =>
    form.items.reduce(
        (sum, item) => sum + Number(item.qty || 0) * Number(item.price || 0),
        0
    )
);

const taxTotal = computed(() =>
    form.items.reduce(
        (sum, item) =>
            sum +
            (Number(item.qty) * Number(item.price) * Number(item.tax || 0)) /
                100,
        0
    )
);

const discountTotal = computed(() => {
    if (!form.discount || !form.discount_type) return 0;
    if (form.discount_type === "percent")
        return (subTotal.value * Number(form.discount)) / 100;
    return Number(form.discount);
});

const grandTotal = computed(() => {
    let total = subTotal.value + taxTotal.value - discountTotal.value;
    if (roundOff.value) {
        form.round_value = (Math.round(total) - total).toFixed(2);
        return Math.round(total);
    }
    form.round_value = 0.0;
    return total;
});

function addItem() {
    form.items.push({
        item_code: "",
        item_name: "",
        qty: 1,
        price: 0,
        tax: 0,
        imei_sl: "",
        note: "",
        warranty_date: "",
    });
}

function removeItem(index) {
    if (form.items.length > 1) form.items.splice(index, 1);
    else alert("At least one item is required.");
}

function submitForm(actionType) {
    form.action = actionType;
    form.sub_total = subTotal.value;
    form.total_discount = discountTotal.value;
    form.total_tax = taxTotal.value;
    form.total = grandTotal.value;

    form.put(route("admin.invoice.update", props.invoice.invoice), {
        onSuccess: () => form.reset(),
    });
}

const showDiscountModal = ref(false);
function openDiscount() {
    showDiscountModal.value = true;
}
function applyDiscount() {
    showDiscountModal.value = false;
}

const customerOptions = props.users.map((u) => ({
    value: u.id,
    label: u.name,
}));

watch(
    () => form.customer_id,
    (val) => {
        const customer = props.users.find((u) => u.id === val);
        if (customer?.address) form.address = customer.address;
    }
);
</script>

<template>
    <Layout>
        <Head title="Edit Billing" />
        <Breadcrumb />

        <FlashMessage
            v-if="$page.props.flash?.status"
            :message="$page.props.flash.status"
            type="success"
        />

        <div class="border border-gray-300 p-2 shadow-sm rounded">
            <div class="flex justify-between items-center border-b mb-4 pb-3">
                <h2 class="text-lg font-bold">
                    <i class="fa fa-pencil"></i> Edit Billing
                </h2>
                <Link :href="route('admin.billing')" class="btn rounded-sm"
                    ><i class="fa-solid fa-list"></i> Invoice List</Link
                >
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div class="flex gap-3">
                    <div class="w-3/4 h-fit">
                        <div class="border border-gray-300 bg-white p-3 mb-3">
                            <h3 class="font-semibold mb-2">Items</h3>
                            <div
                                v-for="(item, index) in form.items"
                                :key="index"
                            >
                                <div class="flex gap-4 mb-2 items-center">
                                    <TextInput
                                        label="Item Code"
                                        v-model="item.item_code"
                                        :error="
                                            form.errors[
                                                `items.${index}.item_code`
                                            ]
                                        "
                                    />
                                    <TextInput
                                        label="Item Name"
                                        v-model="item.item_name"
                                        :error="
                                            form.errors[
                                                `items.${index}.item_name`
                                            ]
                                        "
                                    />
                                    <div>
                                        <label class="form-label"
                                            >Warranty</label
                                        >
                                        <input
                                            type="date"
                                            v-model="item.warranty_date"
                                            class="border rounded p-2 border-gray-400"
                                        />
                                    </div>
                                    <TextInput
                                        label="IMEI/SL"
                                        v-model="item.imei_sl"
                                        :error="
                                            form.errors[
                                                `items.${index}.imei_sl`
                                            ]
                                        "
                                    />
                                    <div>
                                        <label class="form-label">Note</label>
                                        <textarea
                                            v-model="item.note"
                                            class="border rounded p-2 border-gray-400"
                                        ></textarea>
                                    </div>
                                    <TextInput
                                        label="Qty"
                                        type="number"
                                        v-model="item.qty"
                                        :error="
                                            form.errors[`items.${index}.qty`]
                                        "
                                    />
                                    <TextInput
                                        label="Price"
                                        type="number"
                                        v-model="item.price"
                                        :error="
                                            form.errors[`items.${index}.price`]
                                        "
                                    />
                                    <div>
                                        <label class="form-label">Tax</label>
                                        <select
                                            v-model="item.tax"
                                            class="border rounded p-2 border-gray-400"
                                        >
                                            <option value="0">0%</option>
                                            <option
                                                :value="$page.props.site?.tax"
                                            >
                                                {{ $page.props.site?.tax }} %
                                            </option>
                                        </select>
                                    </div>
                                    <button
                                        type="button"
                                        @click="removeItem(index)"
                                        class="text-red-500"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <button
                                type="button"
                                @click="addItem"
                                class="btn rounded-sm"
                            >
                                + Add Item
                            </button>
                        </div>

                        <div class="border border-gray-300 bg-white p-3">
                            <RichTextEditor
                                v-model="form.terms"
                                label="Terms & Conditions"
                                placeholder="Enter Terms"
                                :error="form.errors.terms"
                            />
                        </div>
                    </div>

                    <div class="w-1/4 h-fit">
                        <div
                            class="flex gap-5 justify-end p-3 border border-gray-300 bg-white"
                        >
                            <button
                                type="button"
                                @click="submitForm('save')"
                                class="btn rounded-sm flex justify-center items-center gap-3"
                                :disabled="form.processing"
                                :class="{ 'btn-spinner': form.processing }"
                            >
                                <span>{{
                                    form.processing ? "Updating..." : "Update"
                                }}</span>
                                <span
                                    v-if="form.processing"
                                    class="spinner"
                                ></span>
                            </button>

                            <button
                                type="button"
                                @click="submitForm('save_close')"
                                class="btn rounded-sm flex justify-center items-center gap-3"
                                :disabled="form.processing"
                                :class="{ 'btn-spinner': form.processing }"
                            >
                                <span>{{
                                    form.processing
                                        ? "Update & Closing..."
                                        : "Update & Close"
                                }}</span>
                                <span
                                    v-if="form.processing"
                                    class="spinner"
                                ></span>
                            </button>
                        </div>

                        <div
                            class="p-3 border border-gray-300 bg-white mt-2 flex justify-between"
                        >
                            <label class="form-label cursor-pointer">
                                <input type="checkbox" v-model="roundOff" />
                                Round Off
                            </label>
                            <div class="text-end">
                                <p class="text-xl">
                                    Sub Total: {{ subTotal.toFixed(2) }}
                                </p>
                                <p class="text-xl">
                                    Discount: {{ discountTotal.toFixed(2) }}
                                </p>
                                <p class="text-xl">
                                    Tax: {{ taxTotal.toFixed(2) }}
                                </p>
                                <p class="font-bold text-2xl">
                                    TOTAL: {{ grandTotal.toFixed(2) }}
                                </p>
                            </div>
                        </div>

                        <div class="border border-gray-300 bg-white p-3 mt-3">
                            <div class="mb-3">
                                <SelectInput
                                    v-model="form.customer_id"
                                    :options="customerOptions"
                                    label="Customer Name"
                                    placeholder="Select Customer"
                                    :error="form.errors.customer_id"
                                />
                            </div>
                            <div class="mb-3">
                                <label class="form-label flex items-center">
                                    Address
                                    <i
                                        class="fa-solid fa-circle-exclamation text-indigo-600"
                                        title="Auto generate Address Base in User."
                                    ></i>
                                </label>
                                <textarea
                                    v-model="form.address"
                                    class="border rounded p-2 w-full border-gray-300 focus:outline-0"
                                    readonly
                                ></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Invoice No</label>
                                <input
                                    type="text"
                                    v-model="form.invoice"
                                    placeholder="Invoice No"
                                    class="border border-gray-300 rounded p-2 w-full focus:outline-0"
                                    readonly
                                />
                                <small class="text-yellow-400"
                                    >Keep it Blank to Generate Invoice Number
                                    Automatically</small
                                >
                            </div>
                            <div class="mb-3">
                                <TextInput
                                    label="Paid Amount"
                                    v-model="form.paid"
                                    :error="form.errors.paid"
                                />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Invoice Date</label>
                                <input
                                    type="date"
                                    v-model="form.invoice_date"
                                    class="border rounded p-2 border-gray-300 w-full"
                                />
                                <small
                                    class="text-red-500"
                                    v-if="form.errors.invoice_date"
                                    >{{ form.errors.invoice_date }}</small
                                >
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select
                                    v-model="form.status"
                                    class="border rounded p-2 border-gray-300 w-full"
                                >
                                    <option value="due">Due</option>
                                    <option value="unpaid">Unpaid</option>
                                    <option value="paid">Paid</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                <small
                                    class="text-red-500"
                                    v-if="form.errors.status"
                                    >{{ form.errors.status }}</small
                                >
                            </div>
                            <button
                                type="button"
                                class="btn rounded-sm mt-3"
                                @click="openDiscount"
                            >
                                <i class="fa fa-plus"></i> Add Discount
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Discount modal -->
        <div
            v-if="showDiscountModal"
            class="fixed inset-0 bg-black/50 flex justify-center items-center"
        >
            <div class="bg-white p-5 rounded shadow-lg w-96">
                <h2 class="text-lg font-bold mb-3">Apply Discount</h2>
                <div class="mb-3">
                    <label class="form-label">Discount Type</label>
                    <select
                        v-model="form.discount_type"
                        class="border rounded p-2 border-gray-400 w-full"
                    >
                        <option value="">Select Type</option>
                        <option value="percent">Percent (%)</option>
                        <option value="fixed">Fixed Amount</option>
                    </select>
                </div>
                <TextInput
                    label="Discount Amount"
                    v-model="form.discount"
                    type="number"
                />
                <div class="flex justify-end gap-3 mt-4">
                    <button
                        class="btn rounded-sm"
                        @click="showDiscountModal = false"
                    >
                        Cancel
                    </button>
                    <button class="btn rounded-sm" @click="applyDiscount">
                        Apply
                    </button>
                </div>
            </div>
        </div>
    </Layout>
</template>
