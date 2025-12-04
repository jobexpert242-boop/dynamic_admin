<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({});

function clearCache() {
    form.post(route("clear-cache"));
}
</script>

<template>
    <div class="pt-3 border-t">
        <button
            class="btn-success flex justify-center items-center gap-4"
            :class="{ 'btn-spinner': form.processing }"
            :disabled="form.processing"
            @click="clearCache"
        >
            <span v-if="form.processing">Clearing...</span>
            <span v-else>Cache Clear</span>
        </button>
    </div>
</template>
