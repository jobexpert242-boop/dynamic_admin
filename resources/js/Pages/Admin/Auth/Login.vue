<script setup>
import { useForm, Link, Head } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import TextInput from "@/Shared/TextInput.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";

defineOptions({
    layout: null,
});

const form = useForm({
    login: "",
    password: "",
    remember: false,
});

function submit() {
    form.post("/admin/login");
}
</script>

<template>
    <Head title="Login Page" />
    <div class="min-h-screen flex items-center justify-center bg-gray-300">
        <div class="w-full max-w-md bg-white shadow-md rounded-lg p-8">
            <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">
                Login to Your Account
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
                        id="login"
                        label="Username or Email"
                        v-model="form.login"
                        :error="form.errors.login"
                        placeholder="Enter your username or email"
                        @input="form.errors.login = null"
                    />
                </div>

                <div class="mb-6">
                    <TextInput
                        id="password"
                        label="Password"
                        type="password"
                        v-model="form.password"
                        :error="form.errors.password"
                        placeholder="Enter your password"
                        @input="form.errors.password = null"
                    />
                </div>

                <div class="mb-6">
                    <div class="flex items-center">
                        <input
                            id="remember"
                            type="checkbox"
                            v-model="form.remember"
                            class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                        />
                        <label
                            for="remember"
                            class="ml-2 block text-sm text-gray-700"
                            >Remember Me</label
                        >
                    </div>
                </div>

                <button
                    type="submit"
                    class="btn-success"
                    :class="{ 'btn-spinner': form.processing }"
                    :disabled="form.processing"
                >
                    <span>{{ form.processing ? "Login..." : "Login" }}</span>
                    <span v-if="form.processing" class="spinner"></span>
                </button>
            </form>

            <div class="mt-4 flex justify-between">
                <Link
                    :href="route('admin.register')"
                    class="text-sm text-indigo-600 hover:underline"
                >
                    Don't have an account? Register
                </Link>

                <Link
                    :href="route('admin.forgetpassword')"
                    class="text-sm text-yellow-600 hover:underline"
                >
                    Forget Password
                </Link>
            </div>
        </div>
    </div>
</template>
