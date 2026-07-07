(function () {
    const form = document.getElementById('cdc-form');
    if (!form) return;

    const SAVE_URL = form.dataset.autosaveUrl;
    const PAUSE_BEFORE_SAVING = 3000;
    const SAVE_EVERY = 30000;

    const statusBox     = document.getElementById('autosave-indicator');
    const statusIcon    = document.getElementById('autosave-icon');
    const statusText    = document.getElementById('autosave-text');
    const draftIdField  = document.getElementById('draft_form_id');

    const ICONS = {
        saving: `<svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>`,
        saved:  `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`,
        error:  `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
    };
    const COLORS = {
        saving: 'bg-gray-50 border-gray-200 text-gray-500',
        saved:  'bg-emerald-50 border-emerald-200 text-emerald-700',
        error:  'bg-red-50 border-red-200 text-red-600',
    };

    let currentDraftId = draftIdField.value ? parseInt(draftIdField.value, 10) : null;

    let typingTimer       = null;
    let saveInProgress    = false;
    let hasUnsavedChanges = false;
    function showSaveStatus(state, message) {
        statusBox.className = `flex items-center gap-2 px-3 py-1.5 rounded-lg border text-xs font-medium transition-all duration-300 ${COLORS[state]}`;
        statusIcon.innerHTML = ICONS[state];
        statusText.textContent = message;
    }
    function collectFormData() {
        const data = new FormData(form);
        if (currentDraftId) data.set('draft_form_id', currentDraftId);
        return data;
    }
    async function saveDraft() {
        if (saveInProgress || !hasUnsavedChanges) return;

        saveInProgress = true;
        hasUnsavedChanges = false;
        showSaveStatus('saving', 'Sauvegarde en cours...');

        try {
            const response = await fetch(SAVE_URL, {
                method: 'POST',
                body: collectFormData(),
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });

            if (!response.ok) throw new Error('save failed');

            const result = await response.json();
            currentDraftId = result.form_id;
            draftIdField.value = currentDraftId;
            showSaveStatus('saved', 'Brouillon sauvegardé à ' + result.saved_at);
        } catch {
            hasUnsavedChanges = true;
            showSaveStatus('error', 'Échec de la sauvegarde');
        }

        saveInProgress = false;
    }
    setInterval(saveDraft, SAVE_EVERY);
})();
