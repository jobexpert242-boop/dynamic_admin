<script setup>
import { Head, router, useForm, usePage } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";
import TextInput from "@/Shared/TextInput.vue";
import FileInput from "@/Shared/FileInput.vue";
import { route } from "ziggy-js";
defineProps(['modelValue', 'label', 'error', 'type', 'placeholder', 'id']);

const user = usePage().props.user;

const form = useForm({
    name: user.name,
    image: null,
    old_password: "",
    new_password: "",
    new_password_confirmation: "",
});

function submit() {
    form.post(route("admin.profile.update"), {
        preserveScroll: true,
        onSuccess: () => {
            location.reload();
        },
    });
}
</script>

<template>
    <Layout>
        <Head title="Edit Profile" />
        <FlashMessage
            v-if="$page.props.flash?.status"
            :message="$page.props.flash.status"
            type="success"
        />
        <div class="max-w-xl mx-auto bg-white p-6 rounded shadow border border-gray-300">
            <h2 class="text-xl font-bold mb-4">My Profile</h2>

            <form @submit.prevent="submit" class="space-y-4">
                <div class="mb-4">
                    <TextInput
                        id="name"
                        label="Name"
                        v-model="form.name"
                        :error="form.errors.name"
                        placeholder="Name..."
                    />
                </div>

                <div class="mb-4">
                    <TextInput
                        id="old_password"
                        label="Old Password"
                        type="password"
                        v-model="form.old_password"
                        :error="form.errors.old_password"
                        placeholder="Old Password..."
                    />
                </div>

                <div class="mb-4">
                    <TextInput
                        id="new_password"
                        label="New Password"
                        type="password"
                        v-model="form.new_password"
                        :error="form.errors.new_password"
                        placeholder="New Password..."
                    />
                </div>

                <div class="mb-4">
                    <TextInput
                        id="new_password_confirmation"
                        label="Confirm Password"
                        type="password"
                        v-model="form.new_password_confirmation"
                        :error="form.errors.new_password_confirmation"
                        placeholder="Confirm Password..."
                    />
                </div>

                <div>
                    <FileInput
                        v-model="form.image"
                        label="Profile Picture"
                        accept="image/*"
                        :multiple="false"
                        :error="form.errors.image"
                    />
                    <img
                        v-if="user.image"
                        :src="`/storage/${user.image}`"
                        class="mt-2 w-20 h-20 rounded-full object-cover"
                    />
                </div>

                <button
                    type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
                >
                    Update Profile
                </button>
            </form>
        </div>
    </Layout>
</template>
