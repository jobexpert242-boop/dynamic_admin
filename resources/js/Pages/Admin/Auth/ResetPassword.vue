<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";
import TextInput from "@/Shared/TextInput.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";

const page = usePage();
const form = useForm({
    email: page.props.email || "",
    token: page.props.token || "",
    password: "",
    password_confirmation: "",
});

function submit() {
    form.post("/admin/reset-password");
}
</script>

<template>
    <Head title="ResetPassword Page" />
    <div class="min-h-screen flex items-center justify-center bg-gray-300">
        <div class="w-full max-w-md bg-white shadow-md rounded-lg p-8">
            <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">
                Reset Password
            </h2>
            <FlashMessage
                v-if="$page.props.flash?.status"
                :message="$page.props.flash.status"
                type="success"
            />

            <FlashMessage
                v-if="$page.props.flash?.error"
                :message="$page.props.flash.error"
                type="error"
            />

            <FlashMessage
                v-if="$page.props.flash?.warning"
                :message="$page.props.flash.warning"
                type="warning"
            />
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <TextInput
                        label="Email"
                        type="email"
                        v-model="form.email"
                        :error="form.errors.email"
                        placeholder="Your email"
                        @input="form.errors.email = null"
                    />
                </div>
                <div class="mb-4">
                    <TextInput
                        label="New Password"
                        type="password"
                        v-model="form.password"
                        :error="form.errors.password"
                        placeholder="New password"
                        @input="form.errors.password = null"
                    />
                </div>
                <div class="mb-4">
                    <TextInput
                        label="Confirm Password"
                        type="password"
                        v-model="form.password_confirmation"
                        :error="form.errors.password_confirmation"
                        placeholder="Confirm password"
                        @input="form.errors.password_confirmation = null"
                    />
                </div>
                <button
                    type="submit"
                    class="mt-4 bg-green-600 text-white px-4 py-2 rounded"
                >
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</template>
