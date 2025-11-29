<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import TextInput from "@/Shared/TextInput.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

function submit() {
    form.post("/admin/register");
}
</script>

<template>
    <Head title="Register Page" />
    <div class="min-h-screen flex items-center justify-center bg-gray-300">
        <div class="w-full max-w-md bg-white p-8 rounded shadow">
            <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">
                Create an Account
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
                        label="Name"
                        v-model="form.name"
                        :error="form.errors.name"
                        placeholder="Full Name"
                        @input="form.errors.name = null"
                    />
                </div>

                <div class="mb-4">
                    <TextInput
                        label="Email"
                        type="email"
                        v-model="form.email"
                        :error="form.errors.email"
                        placeholder="example@gmail.com"
                        @input="form.errors.email = null"
                    />
                </div>

                <div class="mb-4">
                    <TextInput
                        label="Password"
                        type="password"
                        v-model="form.password"
                        :error="form.errors.password"
                        placeholder="Choose a password"
                        @input="form.errors.password = null"
                    />
                </div>

                <div class="mb-6">
                    <TextInput
                        label="Confirm Password"
                        type="password"
                        v-model="form.password_confirmation"
                        :error="form.errors.password_confirmation"
                        placeholder="Repeat your password"
                        @input="form.errors.password_confirmation = null"
                    />
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
                >
                    Register
                </button>
            </form>

            <div class="mt-4 text-center">
                <Link
                    :href="route('login')"
                    class="text-sm text-blue-600 hover:underline"
                >
                    Already have an account? Login
                </Link>
            </div>
        </div>
    </div>
</template>
