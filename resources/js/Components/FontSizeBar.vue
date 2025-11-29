<script setup>
import { ref, onMounted } from 'vue';

const fontSizePx = ref(16);
const minSize = 10;
const maxSize = 40;

const top = ref(20);
const left = ref(window.innerWidth / 2 - 100);

const dragging = ref(false);
const position = ref({ x: 0, y: 0 });

const applyFontSize = () => {
  document.documentElement.style.fontSize = `${fontSizePx.value}px`;
  localStorage.setItem('fontSizePx', fontSizePx.value);
};

const increment = () => {
  if (fontSizePx.value < maxSize) {
    fontSizePx.value++;
    applyFontSize();
  }
};

const decrement = () => {
  if (fontSizePx.value > minSize) {
    fontSizePx.value--;
    applyFontSize();
  }
};

const startDrag = (e) => {
  dragging.value = true;
  position.value = { x: e.clientX, y: e.clientY };
};

const onDrag = (e) => {
  if (!dragging.value) return;
  const dx = e.clientX - position.value.x;
  const dy = e.clientY - position.value.y;
  top.value += dy;
  left.value += dx;
  position.value = { x: e.clientX, y: e.clientY };

  localStorage.setItem('fontControllerTop', top.value);
  localStorage.setItem('fontControllerLeft', left.value);
};

const stopDrag = () => {
  dragging.value = false;
};

onMounted(() => {
  const savedSize = localStorage.getItem('fontSizePx');
  fontSizePx.value = savedSize ? parseInt(savedSize) : 16;
  applyFontSize();

  const savedTop = localStorage.getItem('fontControllerTop');
  const savedLeft = localStorage.getItem('fontControllerLeft');
  if (savedTop && savedLeft) {
    top.value = parseInt(savedTop);
    left.value = parseInt(savedLeft);
  }

  window.addEventListener('mousemove', onDrag);
  window.addEventListener('mouseup', stopDrag);
});
</script>

<template>
  <div
    id="font-controller"
    @mousedown="startDrag"
    class="fixed bg-white text-gray-800 shadow-lg rounded-full px-4 py-2 flex items-center gap-4 cursor-move z-50"
    :style="{ top: `${top}px`, left: `${left}px` }"
  >
    <button @click="decrement" class="text-xl text-indigo-700 hover:text-red-500">
      <i class="fa-solid fa-minus"></i>
    </button>
    <span class="font-semibold">{{ fontSizePx }}px</span>
    <button @click="increment" class="text-xl text-indigo-700 hover:text-green-500">
      <i class="fa-solid fa-plus"></i>
    </button>
  </div>
</template>