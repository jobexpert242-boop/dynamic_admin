<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import Breadcrumb from "@/Shared/Breadcrumb.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";
import Layout from "@/Shared/Layout.vue";

const { props } = usePage();
const notifications = props.notifications;
</script>

<template>
  <Layout>
    <Head title="Notifications" />
    <Breadcrumb />

    <FlashMessage
      v-if="$page.props.flash?.status"
      :message="$page.props.flash.status"
      type="success"
    />

    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">Notifications</h1>
      <ul>
        <li
          v-for="notification in notifications"
          :key="notification.id"
          class="mb-4 p-4 border rounded shadow-sm bg-white"
        >
          <p class="font-semibold text-blue-600">{{ notification.data.message }}</p>
          <p class="text-sm text-gray-600">Type: {{ notification.data.type }}</p>

          <div v-if="notification.data.user_name">
            <p class="text-sm">User: {{ notification.data.user_name }}</p>
            <p class="text-sm">Email: {{ notification.data.user_email }}</p>
          </div>

          <p class="text-xs text-gray-500">
            {{ new Date(notification.created_at).toLocaleString() }}
          </p>
        </li>
      </ul>
    </div>
  </Layout>
</template>