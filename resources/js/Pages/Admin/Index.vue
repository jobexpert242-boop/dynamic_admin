<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";
import Breadcrumb from "@/Shared/Breadcrumb.vue";

const page = usePage();

const t = (key) => page.props.translations.messages[key] ?? key;
</script>

<template>
    <Head title="Admin Dashboard" />
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
    <div>
        <Layout>
            <Breadcrumb />

            <h1 class="text-2xl text-black">{{ t("welcome") }} sojib middle</h1>
            <p class="text-lg text-red-500">Sojib</p>
            <span class="text-gray-100">sojkibbbb</span>

        </Layout>
    </div>
</template>
