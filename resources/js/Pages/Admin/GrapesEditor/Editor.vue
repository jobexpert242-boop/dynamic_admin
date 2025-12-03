<template>
    <div>
        <Head :title="page.id ? `Edit: ${page.title}` : 'Create Page'" />

        <!-- <FlashMessage
            v-if="flashMessage"
            :message="flashMessage"
            type="success"
        /> -->
        <FlashMessage
            v-if="$page.props.flash?.status"
            :message="$page.props.flash.status"
            type="success"
        />

        <Layout>
            <Breadcrumb />

            <div class="h-screen flex flex-col">
                <!-- top toolbar -->
                <div
                    class="p-3 flex items-center justify-between border-b bg-white z-20"
                >
                    <div class="flex items-center gap-2">
                        <input
                            v-model="title"
                            class="border p-2 rounded"
                            placeholder="Page title"
                            required
                        />
                        <select
                            v-model="device"
                            @change="setDevice"
                            class="border p-2 rounded"
                        >
                            <option value="desktop">Desktop</option>
                            <option value="tablet">Tablet</option>
                            <option value="mobile">Mobile</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <Link
                            :href="route('pages.grapesjs')"
                            class="px-3 py-1 border rounded"
                            >Show all</Link
                        >

                        <button
                            @click="undo"
                            title="Undo"
                            class="px-3 py-1 border rounded"
                        >
                            Undo
                        </button>
                        <button
                            @click="redo"
                            title="Redo"
                            class="px-3 py-1 border rounded"
                        >
                            Redo
                        </button>

                        <button
                            @click="openAssetUploader"
                            title="Upload Image"
                            class="px-3 py-1 border rounded"
                        >
                            Upload
                        </button>
                        <button
                            @click="openMedia"
                            title="Media Library"
                            class="px-3 py-1 border rounded"
                        >
                            Media
                        </button>

                        <button
                            @click="save"
                            title="Save"
                            class="px-3 py-1 border bg-green-600 text-white rounded"
                        >
                            Save
                        </button>
                        <button
                            @click="exportPage"
                            title="Export"
                            class="px-3 py-1 border rounded"
                        >
                            Export
                        </button>
                    </div>
                </div>

                <div ref="editorContainer" class="flex-1 overflow-hidden"></div>
            </div>
        </Layout>
    </div>
</template>

<script setup>
import { ref, onMounted, nextTick, watch } from "vue";
import grapesjs from "grapesjs";
import "grapesjs/dist/css/grapes.min.css";

import preset from "grapesjs-preset-webpage";
import blocksBasic from "grapesjs-blocks-basic";
import navbar from "grapesjs-navbar";
import forms from "grapesjs-plugin-forms";
import exportPlugin from "grapesjs-plugin-export";
import customCode from "grapesjs-custom-code";
import countdown from "grapesjs-component-countdown";
import typed from "grapesjs-typed";
import tooltip from "grapesjs-tooltip";
import tabs from "grapesjs-tabs";
import styleBg from "grapesjs-style-bg";
import touch from "grapesjs-touch";

import axios from "axios";
import { Head, Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";

import Breadcrumb from "@/Shared/Breadcrumb.vue";
import FlashMessage from "@/Shared/FlashMessage.vue";
import Layout from "@/Shared/Layout.vue";

const props = defineProps({
    page: { type: Object, required: true },
    flash: { type: Object, default: () => ({}) },
});

const page = props.page;
const flash = props.flash;

const title = ref(page.title || "");
const device = ref("desktop");
const editor = ref(null);
const editorContainer = ref(null);
const flashMessage = ref(null);

const csrf =
    document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content") || "";

const namedColorMap = {
    black: "#000000",
    white: "#ffffff",
    red: "#ff0000",
    green: "#00ff00",
    blue: "#0000ff",
    gray: "#808080",
    silver: "#c0c0c0",
    yellow: "#ffff00",
};

function normalizeNamedColorsInHtml(html = "") {
    if (!html) return html;
    Object.keys(namedColorMap).forEach((name) => {
        const hex = namedColorMap[name];
        html = html.replace(
            new RegExp(`(?<![#\\w-])${name}(?![#\\w-])`, "gi"),
            hex
        );
    });
    return html;
}

function normalizeNamedColorsInJson(json) {
    if (!json) return json;

    const walk = (obj) => {
        if (Array.isArray(obj)) return obj.map(walk);
        if (obj && typeof obj === "object") {
            const res = {};
            for (const k in obj) res[k] = walk(obj[k]);
            return res;
        }
        if (typeof obj === "string" && namedColorMap[obj.toLowerCase()]) {
            return namedColorMap[obj.toLowerCase()];
        }
        return obj;
    };

    return walk(json);
}

onMounted(async () => {
    await nextTick();

    const initialHtml = normalizeNamedColorsInHtml(page.html || "");
    const initialJson = normalizeNamedColorsInJson(page.json || {});

    editor.value = grapesjs.init({
        container: editorContainer.value,
        fromElement: false,
        height: "100%",
        showOffsets: true,
        noticeOnUnload: 0,

        plugins: [
            preset,
            blocksBasic,
            navbar,
            forms,
            exportPlugin,
            customCode,
            countdown,
            typed,
            tooltip,
            tabs,
            styleBg,
            touch,
        ],

        storageManager: {
            type: "remote",
            autosave: false,
            autoload: !!page.id,
            urlLoad: page.id ? route("pages.grapes-load", page.id) : undefined,
            urlStore: page.id
                ? route("pages.grapes-store", page.id)
                : undefined,
            contentTypeJson: true,
            headers: {
                "X-CSRF-TOKEN": csrf,
                Accept: "application/json",
            },
            onLoadError: (err) => {
                console.error("GrapesJS load error", err);
                alert(
                    "Failed to load page JSON. Maybe the session expired or page not found."
                );
            },
        },
        components: initialHtml || "",
        style: initialJson.style || [],
    });

    try {
        editor.value.BlockManager.add("cta-block", {
            label: "CTA Block",
            category: "Custom",
            attributes: { class: "fa fa-bullhorn" },
            content: `<section class="gjs-block-cta" style="padding:3rem;text-align:center;background:#f3f3f3">
                  <h2>Call to action</h2>
                  <p>Short description here</p>
                </section>`,
        });
    } catch (e) {
        console.warn("BlockManager add failed", e);
    }

    editor.value.Commands.add("upload-asset", {
        run(ed, sender) {
            sender && sender.set("active", 0);
            openAssetUploaderInternal(ed);
        },
    });

    editor.value.Panels.addButton("options", [
        {
            id: "custom-upload",
            className: "fa fa-image",
            command: "upload-asset",
            attributes: { title: "Upload Image" },
        },
    ]);
});

function setDevice() {
    if (!editor.value) return;
    editor.value.setDevice(device.value);
}

function undo() {
    editor.value && editor.value.runCommand("core:undo");
}

function redo() {
    editor.value && editor.value.runCommand("core:redo");
}

function openMedia() {
    window.open("/admin/media", "_blank");
}

function openAssetUploaderInternal(ed) {
    const input = document.createElement("input");
    input.type = "file";
    input.accept = "image/*";
    input.onchange = async (e) => {
        const file = e.target.files[0];
        if (!file) return;
        const form = new FormData();
        form.append("file", file);
        try {
            const res = await axios.post("/admin/upload-image", form, {
                headers: {
                    "Content-Type": "multipart/form-data",
                    "X-CSRF-TOKEN": csrf,
                },
            });
            if (res.data?.url) {
                ed.AssetManager.add({ src: res.data.url });
                ed.Components.add({
                    type: "image",
                    tagName: "img",
                    attributes: { src: res.data.url },
                });
            } else {
                alert("Upload failed");
            }
        } catch (err) {
            console.error(err);
            alert("Upload error");
        }
    };
    input.click();
}

function openAssetUploader() {
    if (!editor.value) return;
    openAssetUploaderInternal(editor.value);
}

async function save() {
    if (!editor.value) return alert("Editor not ready");

    const html = editor.value.getHtml();
    const css = editor.value.getCss();
    const projectData =
        typeof editor.value.getProjectData === "function"
            ? editor.value.getProjectData()
            : {
                  components: editor.value.getComponents(),
                  style: editor.value.getStyle(),
              };

    const payload = {
        title: title.value || page.title || "Untitled",
        html: `<style>${css}</style>${html}`,
        json: projectData,
    };

    try {
        if (!page.id) {
            const res = await axios.post("/admin/pages", payload, {
                headers: { "X-CSRF-TOKEN": csrf },
            });
            if (res.data?.page?.id) {
                window.location.href = `/admin/pages/${res.data.page.id}/edit`;
                return;
            } else {
                window.location.reload();
                return;
            }
        }

        await axios.put(`/admin/pages/${page.id}`, payload, {
            headers: { "X-CSRF-TOKEN": csrf },
        });

        try {
            editor.value.StorageManager.store();
        } catch (e) {}

        flashMessage.value = "Saved successfully";
        setTimeout(() => (flashMessage.value = null), 2500);
    } catch (err) {
        console.error(err);
        const msg =
            err?.response?.data?.message ||
            err?.response?.data?.error ||
            err?.message ||
            "Save failed";
        alert(`Save failed: ${msg}`);
    }
}

function exportPage() {
    if (!editor.value) return;

    const html = editor.value.getHtml();
    const css = editor.value.getCss();
    const projectData =
        typeof editor.value.getProjectData === "function"
            ? editor.value.getProjectData()
            : {
                  components: editor.value.getComponents(),
                  style: editor.value.getStyle(),
              };

    const payload = {
        title: title.value || page.title,
        html: `<style>${css}</style>${html}`,
        json: projectData,
    };

    const blob = new Blob([JSON.stringify(payload, null, 2)], {
        type: "application/json",
    });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = `${page.slug || title.value || "page"}.json`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

watch(title, (v) => {
    page.title = v;
});
</script>

<style>
.gjs-frame,
.gjs-cv-canvas,
.gjs-editor {
    height: 100%;
}
</style>
