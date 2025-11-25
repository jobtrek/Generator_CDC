import { marked } from 'marked';
import DOMPurify from 'dompurify';
import hljs from 'highlight.js';

marked.setOptions({
    highlight: function(code, lang) {
        if (lang && hljs.getLanguage(lang)) {
            try {
                return hljs.highlight(code, { language: lang }).value;
            } catch (err) {}
        }
        return code;
    },
    breaks: true,
    gfm: true
});

export function markdownEditor() {
    return {
        content: '',
        previewMode: 'split',

        init() {
            const textarea = this.$refs.textarea;
            if (textarea) {
                this.content = textarea.value || '';
            }
        },

        get renderedMarkdown() {
            if (!this.content) return '<p class="text-gray-400 italic">Aucun contenu à prévisualiser...</p>';

            try {
                const html = marked.parse(this.content);
                return DOMPurify.sanitize(html);
            } catch (error) {
                return '<p class="text-red-500">Erreur de rendu Markdown</p>';
            }
        },

        insertMarkdown(syntax) {
            const textarea = this.$refs.textarea;
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selectedText = this.content.substring(start, end);

            let newText = '';
            let cursorOffset = 0;

            switch(syntax) {
                case 'bold':
                    newText = `**${selectedText || 'texte en gras'}**`;
                    cursorOffset = selectedText ? newText.length : 2;
                    break;
                case 'italic':
                    newText = `*${selectedText || 'texte en italique'}*`;
                    cursorOffset = selectedText ? newText.length : 1;
                    break;
                case 'heading':
                    newText = `## ${selectedText || 'Titre'}`;
                    cursorOffset = selectedText ? newText.length : 3;
                    break;
                case 'list':
                    newText = selectedText
                        ? selectedText.split('\n').map(line => `- ${line}`).join('\n')
                        : '- Élément de liste';
                    cursorOffset = newText.length;
                    break;
                case 'ordered-list':
                    newText = selectedText
                        ? selectedText.split('\n').map((line, i) => `${i+1}. ${line}`).join('\n')
                        : '1. Premier élément';
                    cursorOffset = newText.length;
                    break;
                case 'link':
                    newText = `[${selectedText || 'texte du lien'}](url)`;
                    cursorOffset = selectedText ? newText.length - 4 : newText.length - 4;
                    break;
                case 'code':
                    newText = '```\n' + (selectedText || 'code') + '\n```';
                    cursorOffset = selectedText ? newText.length - 4 : 4;
                    break;
                case 'quote':
                    newText = selectedText
                        ? selectedText.split('\n').map(line => `> ${line}`).join('\n')
                        : '> Citation';
                    cursorOffset = newText.length;
                    break;
            }

            this.content = this.content.substring(0, start) + newText + this.content.substring(end);

            this.$nextTick(() => {
                textarea.focus();
                textarea.selectionStart = textarea.selectionEnd = start + cursorOffset;
            });
        },

        togglePreview(mode) {
            this.previewMode = mode;
        }
    };
}

window.markdownEditor = markdownEditor;
