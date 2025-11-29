<script setup>
import { ref, onMounted } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { route } from "ziggy-js";

const count = ref(0);
const { props } = usePage();
const userId = props.auth.user.id;

onMounted(() => {
    window.Echo.private(`notifications.${userId}`)
        .listen('NewNotification', (e) => {
            count.value += 1;
        });
});

const goToNotifications = () => {
    count.value = 0;
    router.visit(route('notifications.index'));
};
</script>

<template>
    <div class="inline-block">
        <button type="button" class="relative" @click="goToNotifications">
            <i class="fa-solid fa-bell text-2xl"></i>

            <!-- Badge -->
            <span
                v-if="count > 0"
                class="absolute -top-2 -right-1 bg-red-500 text-white text-xs h-5 w-5 rounded-full flex items-center justify-center"
            >
                {{ count }}
            </span>
        </button>
    </div>
</template>
