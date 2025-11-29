<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";

const open = ref(false);
const position = ref({ top: "0px", left: "0px" });
const triggerRef = ref(null);

function toggle(event) {
  open.value = !open.value;
  if (open.value && triggerRef.value) {
    const rect = triggerRef.value.getBoundingClientRect();
    position.value = {
      top: `${rect.bottom + window.scrollY}px`,
      left: `${rect.left + window.scrollX}px`,
    };
  }
}

function close() {
  open.value = false;
}

function handleClickOutside(event) {
  const dropdown = document.querySelector(".dropdown-panel");
  if (
    open.value &&
    dropdown &&
    !dropdown.contains(event.target) &&
    !triggerRef.value.contains(event.target)
  ) {
    close();
  }
}

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener("click", handleClickOutside);
});
</script>

<template>
  <div class="relative">
    <button ref="triggerRef" type="button" @click="toggle">
      <slot />
    </button>

    <teleport to="body">
      <div
        v-if="open"
        class="absolute z-50 dropdown-panel"
        :style="{ top: position.top, left: position.left, width: '170px' }"
        @click.stop
      >
        <slot name="dropdown" />
      </div>
    </teleport>
  </div>
</template>
