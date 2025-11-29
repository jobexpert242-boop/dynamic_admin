<script setup>
import { ref, onMounted } from "vue";

const props = defineProps({
    message: String,
    type: {
        type: String,
        default: "success",
    },
});

const visible = ref(true);

onMounted(() => {
    setTimeout(() => {
        visible.value = false;
    }, 5000);
});

const typeClass = {
    success: "bg-green-100 text-green-700 border border-green-400",
    error: "bg-red-100 text-red-700 border border-red-400",
    warning: "bg-yellow-100 text-yellow-700 border border-yellow-400",
}[props.type];

const iconClass = {
    success: "fas fa-check-circle",
    error: "fas fa-exclamation-circle",
    warning: "fas fa-exclamation-triangle",
}[props.type];
</script>

<template>
    <transition name="fade">
        <div
            v-if="visible"
            :class="[
                'fixed top-4 right-4 z-999 px-4 py-3 rounded shadow-md flex items-center space-x-2',
                typeClass,
            ]"
        >
            <i :class="iconClass"></i>
            <span>{{ message }}</span>
        </div>
    </transition>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/*
<FlashMessage
    v-if="$page.props.flash?.status"
    :message="$page.props.flash.status"
    type="success"
/>*/
</style>
