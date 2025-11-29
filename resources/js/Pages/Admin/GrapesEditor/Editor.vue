<template>
     <div>
        <Head title="Create User Page" />
        <FlashMessage
            v-if="$page.props.flash?.status"
            :message="$page.props.flash.status"
            type="success"
        />
        <Layout>
            <Breadcrumb />
            <div class="h-screen flex flex-col">
                <div class="p-3 flex items-center justify-between border-b bg-white z-20">
                <div class="flex items-center gap-2">
                    <input v-model="title" class="border p-2 rounded" placeholder="Page title" />
                    <select v-model="device" @change="setDevice">
                    <option value="desktop">Desktop</option>
                    <option value="tablet">Tablet</option>
                    <option value="mobile">Mobile</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button @click="undo" title="Undo" class="px-3 py-1 border rounded">Undo</button>
                    <button @click="redo" title="Redo" class="px-3 py-1 border rounded">Redo</button>
                    <button @click="openAssetUploader" title="Upload Image" class="px-3 py-1 border rounded">Upload</button>
                    <button @click="openMedia" title="Media Library" class="px-3 py-1 border rounded">Media</button>
                    <button @click="save" title="Save" class="px-3 py-1 border bg-green-600 text-white rounded">Save</button>
                    <button @click="exportPage" title="Export" class="px-3 py-1 border rounded">Export</button>
                    <a :href="`/p/${page.slug}`" target="_blank" class="px-3 py-1 border rounded">Preview</a>
                </div>
                </div>

                <div ref="editorContainer" class="flex-1 overflow-hidden"></div>
            </div>
        </Layout>
     </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import grapesjs from 'grapesjs'
import 'grapesjs/dist/css/grapes.min.css'
import preset from 'grapesjs-preset-webpage'
import blocksBasic from 'grapesjs-blocks-basic'
import navbar from 'grapesjs-navbar'
import forms from 'grapesjs-plugin-forms'
import exportPlugin from 'grapesjs-plugin-export'
import gjsCkeditor from 'grapesjs-plugin-ckeditor'
import customCode from 'grapesjs-custom-code'
import countdown from 'grapesjs-component-countdown'
import typed from 'grapesjs-typed'
import tooltip from 'grapesjs-tooltip'
import tabs from 'grapesjs-tabs'
import styleBg from 'grapesjs-style-bg'
import touch from 'grapesjs-touch'
import axios from 'axios'
import Breadcrumb from "@/Shared/Breadcrumb.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";
import Layout from "@/Shared/Layout.vue";
import { Head } from '@inertiajs/vue3'

const props = defineProps({
  page: {
    type: Object,
    default: () => ({
      id: null,
      title: 'New Page',
      slug: 'new-page',
      html: '',
      json: {},
    })
  }
})

const title = ref(props.page?.title ?? 'New Page')
const page = props.page
const editor = ref(null)
const editorContainer = ref(null)
const device = ref('desktop')

const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''

const grapesStoreUrl = (id) => `/admin/pages/${id}/grapes-store`
const grapesLoadUrl = (id) => `/admin/pages/${id}/grapes-load`

onMounted(async () => {
  await nextTick()

  editor.value = grapesjs.init({
    container: editorContainer.value,
    fromElement: false,
    height: '100%',
    showOffsets: true,
    noticeOnUnload: 0,
    plugins: [
      preset,
      blocksBasic,
      navbar,
      forms,
      exportPlugin,
      gjsCkeditor,
      customCode,
      countdown,
      typed,
      tooltip,
      tabs,
      styleBg,
      touch
    ],
    pluginsOpts: {
      gjsCkeditor: { position: 'center' }
    },
    storageManager: {
      id: 'gjs-',
      type: 'remote',
      autosave: false,
      autoload: false,
      stepsBeforeSave: 10,
      urlStore: page.id ? grapesStoreUrl(page.id) : undefined,
      urlLoad: page.id ? grapesLoadUrl(page.id) : undefined,
      contentTypeJson: true,
      headers: {
        'X-CSRF-TOKEN': csrf,
        'Accept': 'application/json'
      }
    },
    components: page.html || '',
  })

  const dm = editor.value.DeviceManager
  dm.add('desktop', { width: '', name: 'Desktop' })
  dm.add('tablet', { width: '768px', name: 'Tablet' })
  dm.add('mobile', { width: '375px', name: 'Mobile' })

  editor.value.Commands.add('undo', {
    run(editor) { editor.UndoManager.undo() }
  })
  editor.value.Commands.add('redo', {
    run(editor) { editor.UndoManager.redo() }
  })

  editor.value.Commands.add('upload-asset', {
    run(ed, sender) {
      sender && sender.set('active', 0)
      const input = document.createElement('input')
      input.type = 'file'
      input.accept = 'image/*'
      input.onchange = async (e) => {
        const file = e.target.files[0]
        if (!file) return
        const form = new FormData()
        form.append('file', file)
        try {
          const res = await axios.post('/admin/upload-image', form, {
            headers: {'Content-Type': 'multipart/form-data', 'X-CSRF-TOKEN': csrf}
          })
          if (res.data?.url) {
            ed.AssetManager.add({ src: res.data.url })
            ed.Components.add({
              type: 'image',
              tagName: 'img',
              attributes: { src: res.data.url }
            })
          } else {
            alert('Upload failed')
          }
        } catch (err) {
          console.error(err)
          alert('Upload error')
        }
      }
      input.click()
    }
  })

  editor.value.Panels.addButton('options', [{
    id: 'custom-upload',
    className: 'fa fa-image',
    command: 'upload-asset',
    attributes: { title: 'Upload Image' }
  }])

  window.__GRAPES_INSERT_IMAGE__ = (url) => {
    if (!editor.value) return
    editor.value.AssetManager.add({ src: url })
    editor.value.Components.add({ type: 'image', tagName: 'img', attributes: { src: url } })
  }

  editor.value.BlockManager.add('cta-block', {
    label: 'CTA Block',
    category: 'Custom',
    attributes: { class: 'fa fa-bullhorn' },
    content: `<section data-block-type="cta" data-props='{"title":"Hello from CTA"}' class="gjs-block-cta" style="padding:3rem;text-align:center;background:#f3f3f3"><h2>Call to action</h2><p>Short description here</p></section>`
  })

  editor.value.on('asset:add', (asset) => {
    // asset.src may be remote url or file object
    // you can intercept and process here if needed
    // console.log('asset added', asset)
  })
})

function undo(){ editor.value && editor.value.runCommand('undo') }
function redo(){ editor.value && editor.value.runCommand('redo') }

function setDevice(){
  if (!editor.value) return
  editor.value.setDevice(device.value)
}

function openAssetUploader(){
  editor.value && editor.value.runCommand('upload-asset')
}

function openMedia(){
  window.open('/admin/media', '_blank')
}

async function save(){
  if (!editor.value) return alert('Editor not ready')
  const html = editor.value.getHtml()
  const css = editor.value.getCss()
  let projectData = {}
  try {
    projectData = typeof editor.value.getProjectData === 'function' ? editor.value.getProjectData() : {
      components: editor.value.getComponents(),
      style: editor.value.getStyle()
    }
  } catch (e) {
    projectData = { components: editor.value.getComponents(), style: editor.value.getStyle() }
  }

  const payload = {
    title: title.value,
    html: '<style>' + css + '</style>' + html,
    json: projectData
  }

  try {
    const id = page.id
    if (!id) {
      const res = await axios.post('/admin/pages', { title: title.value, slug: (page.slug || title.value.toLowerCase().replace(/\s+/g,'-')) }, { headers: { 'X-CSRF-TOKEN': csrf } })
      window.location.reload()
      return
    }
    await axios.put(`/admin/pages/${id}`, payload, { headers: { 'X-CSRF-TOKEN': csrf } })
    alert('Saved')
  } catch (err) {
    console.error(err)
    alert('Save failed: ' + (err?.response?.data?.message || err.message || 'Unknown'))
  }
}

async function exportPage(){
  if (!editor.value) return alert('Editor not ready')
  const html = editor.value.getHtml()
  const css = editor.value.getCss()
  let projectData = {}
  try { projectData = typeof editor.value.getProjectData === 'function' ? editor.value.getProjectData() : { components: editor.value.getComponents(), style: editor.value.getStyle() } } catch(e){ projectData = { components: editor.value.getComponents(), style: editor.value.getStyle() } }

  const payload = { title: title.value, html: '<style>' + css + '</style>' + html, json: projectData }

  const blob = new Blob([JSON.stringify(payload, null, 2)], { type: 'application/json' })
  const link = document.createElement('a')
  link.href = URL.createObjectURL(blob)
  link.download = `${(page.slug || title.value || 'page')}.json`
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
}
</script>

<style scoped>
.gjs-frame,
.gjs-cv-canvas,
.gjs-editor {
  height: 100%;
}
button { cursor: pointer; }
</style>
