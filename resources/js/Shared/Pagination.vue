<script setup>
import { Link } from "@inertiajs/vue3"
import { computed } from "vue"

const props = defineProps({
  links: {
    type: Array,
    default: () => [],
  },
})

function parsePageNumber(label) {
  const num = parseInt(label)
  return isNaN(num) ? null : num
}

const filteredLinks = computed(() => {
  const pages = props.links.map((link, index) => ({
    ...link,
    index,
    page: parsePageNumber(link.label),
    isArrow: parsePageNumber(link.label) === null && link.url !== null,
  }))

  const current = pages.find(p => p.active)?.page ?? 1
  const totalPages = pages.filter(p => p.page !== null).length

  const result = []

  for (let i = 0; i < pages.length; i++) {
    const p = pages[i]

    if (
      p.isArrow || // preserve Previous/Next
      p.page === 1 ||
      p.page === 2 ||
      p.page === 3 ||
      p.page === totalPages ||
      p.page === totalPages - 1 ||
      p.page === totalPages - 2 ||
      Math.abs(p.page - current) <= 1 ||
      p.url === null // disabled arrows
    ) {
      result.push(p)
    } else if (
      result.length === 0 ||
      result[result.length - 1].separator !== true
    ) {
      result.push({ separator: true })
    }
  }

  return result
})
</script>

<template>
  <div v-if="links && links.length > 1" class="flex flex-wrap mt-4 -mb-1 justify-end">
    <template v-for="(link, key) in filteredLinks" :key="key">
      <!-- Ellipsis -->
      <span
        v-if="link.separator"
        class="mb-1 mr-1 px-2 py-1 text-sm text-gray-400 border rounded cursor-default"
      >
        ...
      </span>

      <!-- Disabled -->
      <span
        v-else-if="!link.url"
        class="mb-1 mr-1 px-2 py-1 text-sm text-gray-400 border rounded cursor-default"
        v-html="link.label"
      />

      <!-- Active or Normal -->
      <Link
        v-else
        :href="link.url"
        class="mb-1 mr-1 px-2 py-1 text-sm border rounded"
        :class="{
          'bg-indigo-500 text-white border-indigo-500': link.active,
          'text-gray-700 border-gray-300 hover:bg-gray-100': !link.active,
        }"
        v-html="link.label"
      />
    </template>
  </div>
</template>