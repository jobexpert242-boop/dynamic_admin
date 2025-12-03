<template>
  <div class="fixed right-5 bottom-5 z-50">
    <button
      @click="visible = true"
      class="btn rounded-full"
      title="Accessibility"
    >
      <i class="fa-solid fa-wheelchair"></i>
    </button>
    <div v-if="visible" class="fixed inset-0 bg-black/30 z-40" @click="visible = false"></div>
  </div>

  <transition name="slide">
    <div
      v-if="visible"
      class="fixed top-0 right-0 h-full w-80 bg-white text-gray-900 shadow-xl z-50 p-6 overflow-auto"
      aria-labelledby="accessibility-title"
    >
      <div class="flex justify-between items-center mb-6">
        <h2 id="accessibility-title" class="text-xl font-semibold">Accessibility & Theme</h2>
        <button @click="visible = false" class="px-2 py-1 rounded hover:bg-gray-200 transition">✕</button>
      </div>

      <div class="space-y-6">
        <div>
          <label class="flex items-center justify-between">
            <span class="font-medium">Dark Mode</span>
            <input type="checkbox" v-model="darkMode" class="form-checkbox h-5 w-5" />
          </label>
          <p class="text-sm text-gray-500 mt-1">Toggle site-wide dark mode.</p>
        </div>

        <div>
          <label class="block font-medium mb-2">Font Size</label>
          <select v-model="fontSize" class="w-full p-2 border rounded">
            <option value="text-sm">Small</option>
            <option value="text-base">Medium</option>
            <option value="text-lg">Large</option>
            <option value="text-xl">Extra Large</option>
          </select>
          <p class="text-sm text-gray-500 mt-1">Adjust base font size across the site.</p>
        </div>

        <div>
          <label class="block font-medium mb-2">Layout</label>
          <div class="grid grid-cols-1 gap-2">
            <button @click="layout = 'layout-default'" :class="layoutButtonClass('layout-default')">
              <div class="flex justify-between items-center">
                <div>
                  <div class="font-semibold">Default</div>
                  <div class="text-sm text-gray-500">Standard spacing and padding.</div>
                </div>
                <div class="ml-2 w-16 h-10 bg-gray-500 flex items-center justify-center">A</div>
              </div>
            </button>

            <button @click="layout = 'layout-compact'" :class="layoutButtonClass('layout-compact')">
              <div class="flex justify-between items-center">
                <div>
                  <div class="font-semibold">Compact</div>
                  <div class="text-sm text-gray-500">Tighter spacing for dense screens.</div>
                </div>
                <div class="ml-2 w-16 h-10 bg-gray-500 flex items-center justify-center">A</div>
              </div>
            </button>

            <button @click="layout = 'layout-spacious'" :class="layoutButtonClass('layout-spacious')">
              <div class="flex justify-between items-center">
                <div>
                  <div class="font-semibold">Spacious</div>
                  <div class="text-sm text-gray-500">More padding for readability.</div>
                </div>
                <div class="ml-2 w-16 h-10 bg-gray-500 flex items-center justify-center">A</div>
              </div>
            </button>
          </div>
        </div>

        <div>
          <h3 class="font-medium text-lg mb-2">Theme Colors</h3>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label>Sidebar BG</label>
              <input type="color" v-model="sidebarBg" @input="updateTheme" class="w-full h-8 border rounded"/>
            </div>
            <div>
              <label>Sidebar Text</label>
              <input type="color" v-model="sidebarText" @input="updateTheme" class="w-full h-8 border rounded"/>
            </div>
            <div>
              <label>Button BG</label>
              <input type="color" v-model="btnBg" @input="updateTheme" class="w-full h-8 border rounded"/>
            </div>
            <div>
              <label>Button Text</label>
              <input type="color" v-model="btnText" @input="updateTheme" class="w-full h-8 border rounded"/>
            </div>
            <div>
              <label>Button Hover</label>
              <input type="color" v-model="btnHover" @input="updateTheme" class="w-full h-8 border rounded"/>
            </div>
          </div>
        </div>

        <div class="space-y-2">
          <button @click="resetDefaults" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded transition">Reset Accessibility</button>
          <button @click="resetTheme" class="btn-success">Reset Theme</button>
          <CacheClear/>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, watch, onMounted } from "vue"
import CacheClear from './CacheClear.vue'

const visible = ref(false)
const darkMode = ref(false)
const fontSize = ref("text-base")
const layout = ref("layout-default")

const sidebarBg = ref("#312e81")
const sidebarText = ref("#ffffff")
const btnBg = ref("#312e81")
const btnText = ref("#ffffff")
const btnHover = ref("#282579")

const KEY_DARK = "accessibility:dark"
const KEY_FONT = "accessibility:font"
const KEY_LAYOUT = "accessibility:layout"
const KEY_THEME = "accessibility:theme"

const applyDarkMode = (val) => document.documentElement.classList.toggle("dark", !!val)
const applyFontSize = (cls) => {
  document.body.classList.remove("text-sm", "text-base", "text-lg", "text-xl")
  if (cls) document.body.classList.add(cls)
}
const applyLayout = (cls) => {
  document.documentElement.classList.remove("layout-default", "layout-compact", "layout-spacious")
  if (cls) document.documentElement.classList.add(cls)
}
const updateTheme = () => {
  document.documentElement.style.setProperty("--sidebar-bg", sidebarBg.value)
  document.documentElement.style.setProperty("--sidebar-text", sidebarText.value)
  document.documentElement.style.setProperty("--btn-bg", btnBg.value)
  document.documentElement.style.setProperty("--btn-text", btnText.value)
  document.documentElement.style.setProperty("--btn-hover", btnHover.value)
  const theme = { sidebarBg: sidebarBg.value, sidebarText: sidebarText.value, btnBg: btnBg.value, btnText: btnText.value, btnHover: btnHover.value }
  localStorage.setItem(KEY_THEME, JSON.stringify(theme))
}
const resetTheme = () => { sidebarBg.value="#312e81"; sidebarText.value="#ffffff"; btnBg.value="#2563eb"; btnText.value="#ffffff"; btnHover.value="#1e40af"; updateTheme() }
const resetDefaults = () => { darkMode.value=false; fontSize.value="text-base"; layout.value="layout-default" }

watch(darkMode,(val)=>{ applyDarkMode(val); localStorage.setItem(KEY_DARK, val?"true":"false") })
watch(fontSize,(val)=>{ applyFontSize(val); localStorage.setItem(KEY_FONT,val) })
watch(layout,(val)=>{ applyLayout(val); localStorage.setItem(KEY_LAYOUT,val) })

onMounted(()=>{
  const savedDark = localStorage.getItem(KEY_DARK)
  const savedFont = localStorage.getItem(KEY_FONT)
  const savedLayout = localStorage.getItem(KEY_LAYOUT)
  const savedTheme = localStorage.getItem(KEY_THEME)
  darkMode.value = savedDark==="true"
  fontSize.value = savedFont||"text-base"
  layout.value = savedLayout||"layout-default"
  applyDarkMode(darkMode.value)
  applyFontSize(fontSize.value)
  applyLayout(layout.value)
  if(savedTheme){
    const theme = JSON.parse(savedTheme)
    sidebarBg.value=theme.sidebarBg
    sidebarText.value=theme.sidebarText
    btnBg.value=theme.btnBg
    btnText.value=theme.btnText
    btnHover.value=theme.btnHover
  }
  updateTheme()
})

const layoutButtonClass = (name) => `p-3 rounded border text-left transition ${layout.value===name?"border-indigo-500":"border-gray-300 dark:border-gray-600"}`
</script>

<style scoped>
.slide-enter-active, .slide-leave-active { transition: transform 0.3s ease, opacity 0.3s ease; }
.slide-enter-from, .slide-leave-to { transform: translateX(100%); opacity: 0; }
</style>
