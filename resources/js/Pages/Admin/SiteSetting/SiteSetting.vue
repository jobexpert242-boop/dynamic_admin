<script setup>
import { Head, router, useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import Breadcrumb from "@/Shared/Breadcrumb.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";
import Layout from "@/Shared/Layout.vue";
import FileInput from "@/Shared/FileInput.vue";

const form = useForm({
    logo: null,
    favaicon: null,
});

function submit() {
    form.post(route("admin.sitesetting.update"), {
        preserveScroll: true,
        onSuccess: () => {
            router.reload({ only: ['site'] })
        },
    });
}
</script>

<template>
    <div>
        <Head title="Site Setting Update" />
        <FlashMessage
            v-if="$page.props.flash?.status"
            :message="$page.props.flash.status"
            type="success"
        />
        <Layout>
            <Breadcrumb />
            <div class="p-5 shadow bg-white border border-gray-300 rounded">
                <form
                    @submit.prevent="submit"
                    enctype="multipart/form-data"
                    class="space-y-4"
                >
                    <div class="flex gap-5">
                        <div>
                            <FileInput
                                v-model="form.logo"
                                label="Site Logo"
                                accept="image/*"
                                :multiple="false"
                                :error="form.errors.logo"
                            />
                            <img
                                :src="`/storage/${$page.props.site?.logo}`"
                                class="w-20 h-20 mt-2 border border-slate-300"
                                alt=""
                            />
                        </div>

                        <div>
                            <FileInput
                                v-model="form.favaicon"
                                label="Site Favaicon"
                                accept="image/*"
                                :multiple="false"
                                :error="form.errors.favaicon"
                            />
                            <img
                                :src="`/storage/${$page.props.site?.favaicon}`"
                                class="w-20 h-20 mt-2 border border-slate-300"
                                alt=""
                            />
                        </div>
                    </div>
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
                                form.processing ? "Processing..." : "Update"
                            }}</span>
                            <span v-if="form.processing" class="spinner"></span>
                        </button>
                    </div>
                </form>
            </div>
        </Layout>
    </div>
</template>
