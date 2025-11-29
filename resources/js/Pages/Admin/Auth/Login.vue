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

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition"
                >
                    Login
                </button>
            </form>

            <div class="mt-4 flex justify-between">
                <Link
                    :href="route('admin.register')"
                    class="text-sm text-blue-600 hover:underline"
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
