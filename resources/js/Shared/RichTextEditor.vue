<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from "vue";
import { Editor, EditorContent } from "@tiptap/vue-3";
import { StarterKit } from "@tiptap/starter-kit";
import { Underline } from "@tiptap/extension-underline";
import { Link } from "@tiptap/extension-link";
import { TextAlign } from "@tiptap/extension-text-align";
import { TextStyle } from "@tiptap/extension-text-style";
import { Color } from "@tiptap/extension-color";
import { Image } from "@tiptap/extension-image";
import { Table } from "@tiptap/extension-table";
import { TableRow } from "@tiptap/extension-table-row";
import { TableCell } from "@tiptap/extension-table-cell";
import { TableHeader } from "@tiptap/extension-table-header";
import { Heading } from "@tiptap/extension-heading";
import { CodeBlock } from "@tiptap/extension-code-block";

const props = defineProps({
    modelValue: String,
    error: String,
});

const emit = defineEmits(["update:modelValue"]);
const editor = ref(null);
const isFullscreen = ref(false);

onMounted(() => {
    editor.value = new Editor({
        content: props.modelValue || "",
        extensions: [
            StarterKit.configure({
                heading: false,
                codeBlock: false,
                link: false,
                underline: false,
            }),
            Underline,
            Link.configure({ openOnClick: false }),
            TextAlign.configure({ types: ["heading", "paragraph"] }),
            TextStyle,
            Color,
            Image,
            Table.configure({ resizable: true }),
            TableRow,
            TableCell,
            TableHeader,
            Heading.configure({ levels: [1, 2, 3] }),
            CodeBlock,
        ],
        editorProps: {
            attributes: {
                class: "ProseMirror",
            },
        },
        onUpdate: ({ editor }) => emit("update:modelValue", editor.getHTML()),
    });
});

onBeforeUnmount(() => editor.value?.destroy());

watch(
    () => props.modelValue,
    (v) => {
        if (!editor.value) return;
        if (v !== editor.value.getHTML()) editor.value.commands.setContent(v);
    }
);

// Toolbar actions
const cmd = (name) => editor.value?.chain().focus()[name]().run();
const cmdAlign = (pos) => editor.value?.chain().focus().setTextAlign(pos).run();
const isActive = (type) => (editor.value?.isActive(type) ? "active-btn" : "");
const setColor = (e) =>
    editor.value?.chain().focus().setColor(e.target.value).run();
const setHeading = (level) =>
    editor.value?.chain().focus().toggleHeading({ level }).run();
const addLink = () => {
    const url = prompt("Enter URL:");
    if (url) editor.value?.chain().focus().setLink({ href: url }).run();
};
const uploadImage = (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = () => {
        editor.value?.chain().focus().setImage({ src: reader.result }).run();
    };
    reader.readAsDataURL(file);
};
const addTable = () =>
    editor.value?.chain().focus().insertTable({ rows: 3, cols: 3 }).run();
const toggleFullscreen = () => {
    isFullscreen.value = !isFullscreen.value;
    document.body.style.overflow = isFullscreen.value ? "hidden" : "auto";
};
</script>

<template>
    <div :class="['editor-wrapper', isFullscreen ? 'fullscreen' : '']">
        <!-- Toolbar -->
        <div class="toolbar">
            <!-- Font styles -->
            <button
                type="button"
                :class="isActive('bold')"
                @click="cmd('toggleBold')"
            >
                B
            </button>
            <button
                type="button"
                :class="isActive('italic')"
                @click="cmd('toggleItalic')"
            >
                I
            </button>
            <button
                type="button"
                :class="isActive('underline')"
                @click="cmd('toggleUnderline')"
            >
                U
            </button>

            <!-- Headings -->
            <select @change="setHeading(parseInt($event.target.value))">
                <option value="">Paragraph</option>
                <option value="1">H1</option>
                <option value="2">H2</option>
                <option value="3">H3</option>
            </select>

            <!-- Color -->
            <input type="color" @input="setColor($event)" title="Text Color" />

            <!-- Alignment -->
            <button type="button" @click="cmdAlign('left')">Left</button>
            <button type="button" @click="cmdAlign('center')">Center</button>
            <button type="button" @click="cmdAlign('right')">Right</button>
            <button type="button" @click="cmdAlign('justify')">Justify</button>

            <!-- Lists -->
            <button type="button" @click="cmd('toggleBulletList')">
                • List
            </button>
            <button type="button" @click="cmd('toggleOrderedList')">
                1. List
            </button>

            <!-- Insert -->
            <button type="button" @click="addTable">Table</button>
            <input type="file" @change="uploadImage" title="Insert Image" />
            <button type="button" @click="addLink">Link</button>
            <button type="button" @click="cmd('toggleCodeBlock')">Code</button>
            <button type="button" @click="cmd('setHorizontalRule')">HR</button>

            <!-- Undo/Redo -->
            <button type="button" @click="cmd('undo')">Undo</button>
            <button type="button" @click="cmd('redo')">Redo</button>

            <!-- Fullscreen -->
            <button
                type="button"
                :class="{ 'active-btn': isFullscreen }"
                @click="toggleFullscreen"
            >
                Fullscreen
            </button>
        </div>

        <!-- Editor -->
        <EditorContent :editor="editor" class="editor-content" />

        <p v-if="error" class="text-xs text-red-500 mt-1">{{ error }}</p>
    </div>
</template>

<style scoped>
.dark button {
    background: black;
    color: white;
}
.dark select {
    background: white;
    color: black;
}
.dark input {
    color: black;
}
</style>
<style>
.ProseMirror {
    font-size: 16px;
    line-height: 1.75;
    font-family: "Roboto", sans-serif;
    color: #333;
    outline: none;
    min-height: 400px;
}
.editor-wrapper {
    border: 1px solid #ccc;
    border-radius: 6px;
    background: white;
    padding: 10px;
    font-family: "Segoe UI", sans-serif;
    position: relative;
}

.toolbar {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    background-color: #f1f1f1;
    padding: 8px;
    border-radius: 4px 4px 0 0;
    border: 1px solid #ccc;
    border-bottom: none;
}

.toolbar button,
.toolbar select,
.toolbar input[type="color"] {
    padding: 4px 8px;
    border: 1px solid #bbb;
    background: #f8f8f8;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
}

.toolbar button.active-btn {
    background: #2563eb;
    color: white;
    border-color: #1d4ed8;
}

.editor-content {
    border: 1px solid #ccc;
    border-top: none;
    border-radius: 0 0 4px 4px;
    background: #fff;
    padding: 12px;
    min-height: 400px;
}
.fullscreen {
    position: fixed;
    inset: 0;
    background: white;
    z-index: 9999;
    padding: 20px;
    overflow: auto;
}
.ProseMirror p {
    margin-bottom: 1rem;
    color: black;
}

.ProseMirror h1,
.ProseMirror h2,
.ProseMirror h3 {
    margin: 1.5rem 0 1rem;
    font-weight: bold;
}

.ProseMirror ul,
.ProseMirror ol {
    margin-left: 1.5rem;
    margin-bottom: 1rem;
}
.ProseMirror span[style*="padding"] {
    background-color: #e0f7fa;
    border: 1px solid #ccc;
}
.prose p {
    padding: 8px;
}

.ProseMirror li {
    margin-bottom: 0.5rem;
}

.ProseMirror blockquote {
    margin: 1rem 0;
    padding-left: 1rem;
    border-left: 3px solid #ccc;
    color: #555;
    font-style: italic;
}

.ProseMirror pre {
    background: #f4f4f4;
    padding: 1rem;
    border-radius: 4px;
    font-family: monospace;
    font-size: 14px;
    overflow-x: auto;
    margin-bottom: 1rem;
}

.ProseMirror table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1rem;
}

.ProseMirror th,
.ProseMirror td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
}

.ProseMirror img {
    max-width: 100%;
    height: auto;
    margin: 1rem 0;
}
/*
import RichTextEditor from "@/Shared/RichTextEditor.vue";
<RichTextEditor
    v-model="form.docs"
    label="Documentation"
    placeholder="Write your documentation..."
    :error="form.errors.docs"
/>
*/
</style>
