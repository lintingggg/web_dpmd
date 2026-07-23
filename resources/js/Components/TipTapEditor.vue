<template>
  <div class="border border-[#e3e5e7] rounded-xl overflow-hidden bg-white">
    <!-- Toolbar -->
    <div v-if="editor" class="bg-[#f9f9f9] border-b border-[#e3e5e7] p-2 flex flex-wrap gap-1 items-center">
      <button 
        @click.prevent="editor.chain().focus().toggleHeading({ level: 2 }).run()" 
        :class="['w-8 h-8 rounded flex items-center justify-center font-bold', editor.isActive('heading', { level: 2 }) ? 'bg-[#e3e5e7] text-[#0f172a]' : 'hover:bg-[#e3e5e7] text-[#373f50]']"
        title="Heading 2"
      >
        H2
      </button>
      <button 
        @click.prevent="editor.chain().focus().toggleHeading({ level: 3 }).run()" 
        :class="['w-8 h-8 rounded flex items-center justify-center font-bold', editor.isActive('heading', { level: 3 }) ? 'bg-[#e3e5e7] text-[#0f172a]' : 'hover:bg-[#e3e5e7] text-[#373f50]']"
        title="Heading 3"
      >
        H3
      </button>
      <div class="w-[1px] h-5 bg-[#c8cbd0] mx-2"></div>
      
      <button 
        @click.prevent="editor.chain().focus().toggleBold().run()" 
        :class="['w-8 h-8 rounded flex items-center justify-center font-bold', editor.isActive('bold') ? 'bg-[#e3e5e7] text-[#0f172a]' : 'hover:bg-[#e3e5e7] text-[#373f50]']"
      >
        B
      </button>
      <button 
        @click.prevent="editor.chain().focus().toggleItalic().run()" 
        :class="['w-8 h-8 rounded flex items-center justify-center italic font-serif', editor.isActive('italic') ? 'bg-[#e3e5e7] text-[#0f172a]' : 'hover:bg-[#e3e5e7] text-[#373f50]']"
      >
        I
      </button>
      <button 
        @click.prevent="editor.chain().focus().toggleUnderline().run()" 
        :class="['w-8 h-8 rounded flex items-center justify-center underline', editor.isActive('underline') ? 'bg-[#e3e5e7] text-[#0f172a]' : 'hover:bg-[#e3e5e7] text-[#373f50]']"
      >
        U
      </button>
      
      <div class="w-[1px] h-5 bg-[#c8cbd0] mx-2"></div>
      
      <button 
        @click.prevent="editor.chain().focus().toggleBulletList().run()" 
        :class="['w-8 h-8 rounded flex items-center justify-center', editor.isActive('bulletList') ? 'bg-[#e3e5e7] text-[#0f172a]' : 'hover:bg-[#e3e5e7] text-[#373f50]']"
      >
        <span class="material-symbols-outlined text-[18px]">format_list_bulleted</span>
      </button>
      <button 
        @click.prevent="editor.chain().focus().toggleOrderedList().run()" 
        :class="['w-8 h-8 rounded flex items-center justify-center', editor.isActive('orderedList') ? 'bg-[#e3e5e7] text-[#0f172a]' : 'hover:bg-[#e3e5e7] text-[#373f50]']"
      >
        <span class="material-symbols-outlined text-[18px]">format_list_numbered</span>
      </button>
    </div>
    
    <!-- Content Area -->
    <editor-content :editor="editor" class="p-4 min-h-[250px] max-h-[500px] overflow-y-auto prose max-w-none text-[15px] leading-[1.7] text-[#0f172a]" />
  </div>
</template>

<script setup>
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Underline from '@tiptap/extension-underline'
import { watch, onBeforeUnmount } from 'vue'

const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
})

const emit = defineEmits(['update:modelValue'])

const editor = useEditor({
  content: props.modelValue,
  extensions: [
    StarterKit,
    Underline,
  ],
  onUpdate: () => {
    emit('update:modelValue', editor.value.getHTML())
  },
})

watch(() => props.modelValue, (value) => {
  const isSame = editor.value.getHTML() === value
  if (!isSame) {
    editor.value.commands.setContent(value, false)
  }
})

onBeforeUnmount(() => {
  if (editor.value) {
    editor.value.destroy()
  }
})
</script>

<style>
/* Styling for tiptap editor */
.ProseMirror:focus {
  outline: none;
}
.ProseMirror p {
  margin-bottom: 1rem;
}
.ProseMirror ul {
  list-style-type: disc;
  padding-left: 1.5rem;
  margin-bottom: 1rem;
}
.ProseMirror ol {
  list-style-type: decimal;
  padding-left: 1.5rem;
  margin-bottom: 1rem;
}
.ProseMirror h2 {
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 1rem;
}
.ProseMirror h3 {
  font-size: 1.25rem;
  font-weight: 700;
  margin-bottom: 1rem;
}
</style>
