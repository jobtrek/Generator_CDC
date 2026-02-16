@props([
    'name' => 'content',
    'value' => '',
    'required' => false,
    'placeholder' => 'Écrivez votre contenu en Markdown...',
    'label' => 'Contenu',
    'help' => 'Utilisez Markdown pour formater votre texte',
    'rows' => 10
])

@once
    @push('head-scripts')
        <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.6/dist/purify.min.js"></script>
    @endpush
@endonce

<div class="space-y-2">
    @if($label)
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }} {!! $required ? '<span class="text-red-500">*</span>' : '' !!}
        </label>
    @endif

    <div x-data="markdownEditorComponent({ rows: {{ $rows }} })" class="markdown-editor-wrapper">
        <!-- Toolbar -->
        <div class="bg-gray-50 border border-gray-300 rounded-t-lg p-2 flex items-center gap-1 flex-wrap">
            <button type="button" @click="insertMarkdown('bold')"
                    class="p-2 hover:bg-gray-200 rounded transition" title="Gras (Ctrl+B)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"/>
                </svg>
            </button>

            <button type="button" @click="insertMarkdown('italic')"
                    class="p-2 hover:bg-gray-200 rounded transition" title="Italique (Ctrl+I)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 4h4M14 4l-4 16M10 20h4"/>
                </svg>
            </button>

            <div class="w-px h-6 bg-gray-300 mx-1"></div>

            <button type="button" @click="insertMarkdown('heading')"
                    class="p-2 hover:bg-gray-200 rounded transition font-bold" title="Titre">
                H
            </button>

            <div class="w-px h-6 bg-gray-300 mx-1"></div>

            <button type="button" @click="insertMarkdown('list')"
                    class="p-2 hover:bg-gray-200 rounded transition" title="Liste à puces">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <button type="button" @click="insertMarkdown('ordered-list')"
                    class="p-2 hover:bg-gray-200 rounded transition" title="Liste numérotée">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h12M9 12h12M9 19h12M3 5h.01M3 12h.01M3 19h.01"/>
                </svg>
            </button>

            <div class="w-px h-6 bg-gray-300 mx-1"></div>

            <button type="button" @click="insertMarkdown('link')"
                    class="p-2 hover:bg-gray-200 rounded transition" title="Lien">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
            </button>

            <button type="button" @click="insertMarkdown('code')"
                    class="p-2 hover:bg-gray-200 rounded transition" title="Code">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                </svg>
            </button>

            <button type="button" @click="insertMarkdown('quote')"
                    class="p-2 hover:bg-gray-200 rounded transition" title="Citation">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </button>

            <div class="flex-grow"></div>

            <!-- Mode buttons -->
            <div class="flex gap-1 border-l border-gray-300 pl-2">
                <button type="button" @click="previewMode = 'split'"
                        :class="previewMode === 'split' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700'"
                        class="px-3 py-1 rounded text-xs font-medium transition" title="Mode divisé">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 4v16m6-16v16"/>
                    </svg>
                </button>
                <button type="button" @click="previewMode = 'edit'"
                        :class="previewMode === 'edit' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700'"
                        class="px-3 py-1 rounded text-xs font-medium transition" title="Mode édition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </button>
                <button type="button" @click="previewMode = 'preview'"
                        :class="previewMode === 'preview' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700'"
                        class="px-3 py-1 rounded text-xs font-medium transition" title="Mode prévisualisation">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Editor content -->
        <div class="grid border-x border-b border-gray-300 rounded-b-lg overflow-hidden"
             :class="{
                 'grid-cols-2': previewMode === 'split',
                 'grid-cols-1': previewMode !== 'split'
             }">

            <!-- Textarea -->
            <div x-show="previewMode === 'edit' || previewMode === 'split'">
                <textarea
                    x-ref="textarea"
                    name="{{ $name }}"
                    x-model="content"
                    @input="autoResize()"
                    class="w-full p-4 font-mono text-sm resize-none border-0 focus:ring-0 focus:outline-none overflow-hidden"
                    :style="'min-height: ' + minHeight + 'px'"
                    placeholder="{{ $placeholder }}"
                    {{ $required ? 'required' : '' }}
                >{{ old($name, $value) }}</textarea>
            </div>

            <!-- Preview -->
            <div x-show="previewMode === 'preview' || previewMode === 'split'"
                 class="p-4 bg-gray-50 overflow-y-auto prose prose-sm max-w-none"
                 :class="{'border-l border-gray-300': previewMode === 'split'}"
                 :style="'min-height: ' + minHeight + 'px'"
                 x-html="renderedMarkdown">
            </div>
        </div>
    </div>

    @if($help)
        <p class="text-sm text-gray-500 mt-2">{{ $help }}</p>
    @endif
</div>

@once
    @push('styles')
        <style>
            .markdown-editor-wrapper .prose { max-width: none; }
            .markdown-editor-wrapper .prose h1 { font-size: 1.5rem; font-weight: bold; margin-top: 1.5rem; margin-bottom: 1rem; color: #1f2937; }
            .markdown-editor-wrapper .prose h2 { font-size: 1.25rem; font-weight: bold; margin-top: 1.25rem; margin-bottom: 0.75rem; color: #1f2937; }
            .markdown-editor-wrapper .prose h3 { font-size: 1.125rem; font-weight: 600; margin-top: 1rem; margin-bottom: 0.5rem; color: #1f2937; }
            .markdown-editor-wrapper .prose p { margin-bottom: 1rem; color: #374151; line-height: 1.625; }
            .markdown-editor-wrapper .prose ul { list-style-type: disc; list-style-position: inside; margin-bottom: 1rem; margin-top: 0.5rem; }
            .markdown-editor-wrapper .prose ol { list-style-type: decimal; list-style-position: inside; margin-bottom: 1rem; margin-top: 0.5rem; }
            .markdown-editor-wrapper .prose li { color: #374151; margin-bottom: 0.5rem; }
            .markdown-editor-wrapper .prose blockquote { border-left: 4px solid #6366f1; padding-left: 1rem; font-style: italic; color: #6b7280; margin: 1rem 0; }
            .markdown-editor-wrapper .prose code { background-color: #f3f4f6; padding: 0.125rem 0.5rem; border-radius: 0.25rem; font-size: 0.875rem; font-family: monospace; color: #dc2626; }
            .markdown-editor-wrapper .prose pre { background-color: #1f2937; color: #f3f4f6; padding: 1rem; border-radius: 0.5rem; overflow-x: auto; margin-bottom: 1rem; }
            .markdown-editor-wrapper .prose pre code { background-color: transparent; color: #f3f4f6; padding: 0; }
            .markdown-editor-wrapper .prose a { color: #4f46e5; text-decoration: underline; }
            .markdown-editor-wrapper .prose a:hover { color: #3730a3; }
            .markdown-editor-wrapper .prose strong { font-weight: bold; color: #1f2937; }
            .markdown-editor-wrapper .prose em { font-style: italic; }
            .markdown-editor-wrapper .prose hr { margin: 2rem 0; border-color: #d1d5db; }
        </style>
    @endpush
@endonce

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('markdownEditorComponent', (config = {}) => ({
            content: '',
            previewMode: 'edit',
            rows: config.rows || 10,
            minHeight: (config.rows || 10) * 24,

            init() {
                const textarea = this.$refs.textarea;
                if (textarea && textarea.value) {
                    this.content = textarea.value;
                }
                this.$nextTick(() => this.autoResize());
            },

            autoResize() {
                const textarea = this.$refs.textarea;
                if (textarea) {
                    textarea.style.height = 'auto';
                    const newHeight = Math.max(textarea.scrollHeight, this.minHeight);
                    textarea.style.height = newHeight + 'px';
                }
            },

            get renderedMarkdown() {
                if (!this.content || !this.content.trim()) {
                    return '<p class="text-gray-400 italic">Aucun contenu à prévisualiser...</p>';
                }
                try {
                    if (typeof marked === 'undefined') {
                        return '<p class="text-red-500">Chargement de Markdown...</p>';
                    }
                    const html = marked.parse(this.content);
                    if (typeof DOMPurify !== 'undefined') {
                        return DOMPurify.sanitize(html);
                    }
                    return html;
                } catch (error) {
                    console.error('Erreur Markdown:', error);
                    return '<p class="text-red-500">Erreur de rendu Markdown</p>';
                }
            },

            insertMarkdown(syntax) {
                const textarea = this.$refs.textarea;
                if (!textarea) return;

                const scrollTop = textarea.scrollTop;
                const start = textarea.selectionStart;
                const end = textarea.selectionEnd;
                const selectedText = this.content.substring(start, end);

                let newText = '';
                let selectStart = 0;
                let selectEnd = 0;

                switch(syntax) {
                    case 'bold':
                        newText = `**${selectedText || 'texte en gras'}**`;
                        if (selectedText) { selectStart = start; selectEnd = start + newText.length; }
                        else { selectStart = start + 2; selectEnd = start + newText.length - 2; }
                        break;
                    case 'italic':
                        newText = `*${selectedText || 'texte en italique'}*`;
                        if (selectedText) { selectStart = start; selectEnd = start + newText.length; }
                        else { selectStart = start + 1; selectEnd = start + newText.length - 1; }
                        break;
                    case 'heading':
                        newText = `## ${selectedText || 'Titre'}`;
                        if (selectedText) { selectStart = start; selectEnd = start + newText.length; }
                        else { selectStart = start + 3; selectEnd = start + newText.length; }
                        break;
                    case 'list':
                        newText = selectedText ? selectedText.split('\n').map(line => `- ${line}`).join('\n') : '- Élément de liste';
                        selectStart = start; selectEnd = start + newText.length;
                        break;
                    case 'ordered-list':
                        newText = selectedText ? selectedText.split('\n').map((line, i) => `${i+1}. ${line}`).join('\n') : '1. Premier élément';
                        selectStart = start; selectEnd = start + newText.length;
                        break;
                    case 'link':
                        newText = `[${selectedText || 'texte du lien'}](url)`;
                        if (selectedText) { selectStart = start + newText.length - 4; selectEnd = start + newText.length - 1; }
                        else { selectStart = start + 1; selectEnd = start + 14; }
                        break;
                    case 'code':
                        newText = '```\n' + (selectedText || 'code') + '\n```';
                        if (selectedText) { selectStart = start; selectEnd = start + newText.length; }
                        else { selectStart = start + 4; selectEnd = start + 8; }
                        break;
                    case 'quote':
                        newText = selectedText ? selectedText.split('\n').map(line => `> ${line}`).join('\n') : '> Citation';
                        selectStart = start; selectEnd = start + newText.length;
                        break;
                }

                this.content = this.content.substring(0, start) + newText + this.content.substring(end);

                this.$nextTick(() => {
                    textarea.focus({ preventScroll: true });
                    textarea.setSelectionRange(selectStart, selectEnd);
                    textarea.scrollTop = scrollTop;
                    this.autoResize();
                });
            }
        }));
    });
</script>
