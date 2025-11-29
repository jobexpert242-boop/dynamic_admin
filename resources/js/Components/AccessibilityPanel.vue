<script setup>
import { ref, watch, onMounted } from "vue";

const visible = ref(false);
const darkMode = ref(false);
const fontSize = ref("text-base");
const layout = ref("layout-default");

const KEY_DARK = "accessibility:dark";
const KEY_FONT = "accessibility:font";
const KEY_LAYOUT = "accessibility:layout";

const applyDarkMode = (val) => {
    document.documentElement.classList.toggle("dark", !!val);
};

const applyFontSize = (cls) => {
    document.body.classList.remove(
        "text-sm",
        "text-base",
        "text-lg",
        "text-xl"
    );
    if (cls) document.body.classList.add(cls);
};

const applyLayout = (cls) => {
    document.documentElement.classList.remove(
        "layout-default",
        "layout-compact",
        "layout-spacious"
    );
    if (cls) document.documentElement.classList.add(cls);
};

const toggleQuickDark = () => {
    darkMode.value = !darkMode.value;
};

watch(darkMode, (val) => {
    applyDarkMode(val);
    localStorage.setItem(KEY_DARK, val ? "true" : "false");
});
watch(fontSize, (val) => {
    applyFontSize(val);
    localStorage.setItem(KEY_FONT, val);
});
watch(layout, (val) => {
    applyLayout(val);
    localStorage.setItem(KEY_LAYOUT, val);
});

onMounted(() => {
    const savedDark = localStorage.getItem(KEY_DARK);
    const savedFont = localStorage.getItem(KEY_FONT);
    const savedLayout = localStorage.getItem(KEY_LAYOUT);

    darkMode.value = savedDark === "true";
    fontSize.value = savedFont || "text-base";
    layout.value = savedLayout || "layout-default";

    applyDarkMode(darkMode.value);
    applyFontSize(fontSize.value);
    applyLayout(layout.value);
});
</script>

<template>
    <div class="fixed right-5 bottom-5 z-50">
        <div class="flex flex-col items-end gap-3">
            <!-- <button
        @click="toggleQuickDark"
        class="mb-1 bg-indigo-600 text-white p-3 rounded-full shadow-lg hover:bg-indigo-700 transition"
        title="Toggle Dark Mode"
      >
        <i class="fa-solid fa-moon"></i>
      </button> -->

            <button
                @click="visible = true"
                class="bg-indigo-600 text-white p-3 rounded-full shadow-lg hover:bg-indigo-700 transition"
                title="Accessibility"
            >
                <i class="fa-solid fa-wheelchair"></i>
            </button>
            <!-- Overlay click-to-close -->
    <div 
        v-if="visible"
        class="fixed inset-0 bg-black/30 z-40"
        @click="visible = false"
    ></div>
        </div>
    </div>
    <transition name="slide">
        <aside
            v-if="visible"
            class="fixed top-0 right-0 h-full w-72 bg-white text-gray-900 shadow-xl z-50 p-4 overflow-auto"
            aria-labelledby="accessibility-title"
        >
            <div class="flex justify-between items-center mb-4">
                <h2 id="accessibility-title" class="text-lg font-semibold">
                    Accessibility
                </h2>
                <div class="flex items-center gap-2">
                    <button
                        @click="visible = false"
                        class="px-2 py-1 rounded hover:bg-gray-100 dark:hover:bg-gray-800"
                    >
                        ✕
                    </button>
                </div>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="flex items-center justify-between">
                        <span class="font-medium">Dark Mode</span>
                        <input
                            type="checkbox"
                            v-model="darkMode"
                            class="form-checkbox h-5 w-5"
                        />
                    </label>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Toggle site-wide dark mode.
                    </p>
                </div>

                <div>
                    <label class="block font-medium mb-2">Font Size</label>
                    <select
                        v-model="fontSize"
                        class="w-full p-2 border rounded"
                    >
                        <option value="text-sm">Small</option>
                        <option value="text-base">Medium</option>
                        <option value="text-lg">Large</option>
                        <option value="text-xl">Extra Large</option>
                    </select>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Adjust base font size across the site.
                    </p>
                </div>

                <div>
                    <label class="block font-medium mb-2">Layout</label>
                    <div class="grid grid-cols-1 gap-2">
                        <button
                            @click="layout = 'layout-default'"
                            :class="[
                                'p-3 rounded border text-left',
                                layout === 'layout-default'
                                    ? 'border-indigo-500'
                                    : 'border-gray-200',
                            ]"
                        >
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="font-semibold">Default</div>
                                    <div
                                        class="text-sm text-gray-500"
                                    >
                                        Standard spacing and padding.
                                    </div>
                                </div>
                                <div
                                    class="ml-2 w-16 h-10 accessibility-preview bg-gray-100"
                                >
                                    A
                                </div>
                            </div>
                        </button>

                        <button
                            @click="layout = 'layout-compact'"
                            :class="[
                                'p-3 rounded border text-left',
                                layout === 'layout-compact'
                                    ? 'border-indigo-500'
                                    : 'border-gray-200',
                            ]"
                        >
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="font-semibold">Compact</div>
                                    <div
                                        class="text-sm text-gray-500 dark:text-gray-400"
                                    >
                                        Tighter spacing for dense screens.
                                    </div>
                                </div>
                                <div
                                    class="ml-2 w-16 h-10 accessibility-preview bg-gray-100"
                                >
                                    A
                                </div>
                            </div>
                        </button>

                        <button
                            @click="layout = 'layout-spacious'"
                            :class="[
                                'p-3 rounded border text-left',
                                layout === 'layout-spacious'
                                    ? 'border-indigo-500'
                                    : 'border-gray-200',
                            ]"
                        >
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="font-semibold">Spacious</div>
                                    <div
                                        class="text-sm text-gray-500 dark:text-gray-400"
                                    >
                                        More padding for readability.
                                    </div>
                                </div>
                                <div
                                    class="ml-2 w-16 h-10 accessibility-preview bg-gray-100"
                                >
                                    A
                                </div>
                            </div>
                        </button>
                    </div>
                </div>

                <div class="pt-2 border-t">
                    <button
                        @click="
                            darkMode = false;
                            fontSize = 'text-base';
                            layout = 'layout-default';
                        "
                        class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded"
                    >
                        Reset to defaults
                    </button>
                </div>
            </div>
        </aside>
    </transition>
</template>

<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.25s ease, opacity 0.25s ease;
}
.slide-enter-from,
.slide-leave-to {
    transform: translateX(100%);
    opacity: 0;
}
</style>
