<template>
  <div>
    <div v-if="page.html" v-html="page.html"></div>
    <div v-else>
      <p>No content yet — open the editor to create content.</p>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({ page: Object })
</script>
