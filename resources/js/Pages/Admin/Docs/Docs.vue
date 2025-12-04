<script setup>
import Layout from "@/Shared/Layout.vue";
import { Head, useForm, usePage, router } from "@inertiajs/vue3";
import Breadcrumb from "@/Shared/Breadcrumb.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";
import { route } from "ziggy-js";
import RichTextEditor from "@/Shared/RichTextEditor.vue";

const { site } = usePage().props;

const form = useForm({
    docs: site?.docs || "",
});

function submit() {
    form.post(route("admin.docs.update"), {
        preserveScroll: true,
        onSuccess: () => {
            router.reload({ only: ["site"] });
        },
    });
}
</script>

<template>
    <Layout>
        <Head title="Documentation Settings" />
        <Breadcrumb />

        <FlashMessage
            v-if="$page.props.flash?.status"
            :message="$page.props.flash.status"
            type="success"
        />

        <div class="flex gap-6 mt-6">
            <!-- LEFT -->
            <div
                class="w-1/2 border border-slate-300 p-6 rounded shadow bg-white h-fit"
            >
                <h2 class="text-lg font-semibold mb-2">Current Docs</h2>

                <div
                    v-if="$page.props.site?.docs"
                    v-html="$page.props.site.docs"
                    class="prose max-w-none"
                />

                <p v-else class="text-gray-600">No documentation available.</p>
            </div>

            <!-- RIGHT -->
            <div
                class="w-1/2 bg-white p-6 rounded shadow border border-slate-300 h-fit"
            >
                <h2 class="text-lg font-semibold mb-2">Edit Docs</h2>

                <form @submit.prevent="submit" class="space-y-4">
                    <RichTextEditor
                        v-model="form.docs"
                        label="Documentation"
                        placeholder="Write your documentation..."
                        :error="form.errors.docs"
                    />

                    <div
                        class="border-t border-slate-400 w-full flex justify-end pt-5"
                    >
                        <button
                            type="submit"
                            class="btn rounded-sm fw-normal flex justify-center items-center gap-4"
                            :class="{ 'btn-spinner': form.processing }"
                            :disabled="form.processing"
                        >
                            <span>{{
                                form.processing ? "Processing Documentation..." : "Update Documentation"
                            }}</span>
                            <span v-if="form.processing" class="spinner"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </Layout>
</template>
