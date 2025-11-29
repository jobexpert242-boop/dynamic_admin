<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import TextInput from "@/Shared/TextInput.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";

const form = useForm({ email: "" });

function submit() {
    form.post("/admin/forgetpassword");
}
</script>

<template>
    <Head title="ForgetPassword Page" />
    <div class="min-h-screen flex items-center justify-center bg-gray-300">
        <div class="w-full max-w-md bg-white shadow-md rounded-lg p-8">
            <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">
                Forgot Password
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
                        placeholder="Enter your email"
                        @input="form.errors.email = null"
                    />
                </div>
                <button
                    type="submit"
                    class="mt-4 bg-blue-600 text-white px-4 py-2 rounded"
                >
                    Send Reset Link
                </button>
            </form>
        </div>
    </div>
</template>
