<template>
    <div
        class="min-h-screen flex flex-col items-center justify-center bg-gray-100 dark:bg-gray-900 p-8 text-center"
    >
        <h1 class="text-7xl font-extrabold text-indigo-600 mb-4">
            {{ status }}
        </h1>
        <p class="text-2xl font-semibold text-gray-700 dark:text-gray-300 mb-6">
            {{ title }}
        </p>
        <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-lg">
            {{ description }}
        </p>
        <button
            type="button"
            class="px-6 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700 transition"
            @click="goBack"
        >
            Go Back
        </button>
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    status: {
        type: Number,
        required: true,
    },
    message: {
        type: String,
        default: "",
    },
});

const title = computed(() => {
    return (
        {
            401: "Unauthorized Access",
            403: "Access Forbidden",
            404: "Page Not Found",
            419: "Page Expired",
            429: "Too Many Requests",
            500: "Server Error",
            503: "Service Unavailable",
        }[props.status] || "Unknown Error"
    );
});

const description = computed(() => {
    return (
        {
            401: "You must be logged in to access this page.",
            403: "You don't have permission to view this page.",
            404: "The page you are looking for doesn't exist.",
            419: "Your session has expired. Please refresh the page.",
            429: "You are making too many requests. Try again later.",
            500: "Something went wrong on our server.",
            503: "The server is temporarily offline. Please try again later.",
        }[props.status] ||
        props.message ||
        "An unexpected error occurred."
    );
});

function goBack() {
    window.history.back();
}
</script>
