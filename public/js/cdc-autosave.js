(function () {
    const form = document.getElementById('cdc-form');
    if (!form) return;

    const autosaveUrl = form.dataset.autosaveUrl;
    const indicator  = document.getElementById('autosave-indicator');
    const iconEl     = document.getElementById('autosave-icon');
    const textEl     = document.getElementById('autosave-text');
    const draftInput = document.getElementById('draft_form_id');

    let draftFormId       = null;
    let autosaveTimeout   = null;
    let isSaving          = false;
    let hasUnsavedChanges = false;

    const ICONS = {
        saving: `<svg class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>`,
        saved:  `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`,
        error:  `<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
    };

    function setIndicator(state, text) {
        indicator.classList.remove('hidden');
        indicator.classList.add('flex');

        const styles = {
            saving: 'bg-gray-50 border-gray-200 text-gray-500',
            saved:  'bg-emerald-50 border-emerald-200 text-emerald-700',
            error:  'bg-red-50 border-red-200 text-red-600',
        };

        indicator.className = `flex items-center gap-2 px-3 py-1.5 rounded-lg border text-xs font-medium transition-all duration-300 ${styles[state]}`;
        iconEl.innerHTML = ICONS[state];
        textEl.textContent = text;
    }

    function getFormData() {
        const fd = new FormData(form);
        if (draftFormId) fd.set('draft_form_id', draftFormId);
        return fd;
    }

    async function doAutosave() {
        if (isSaving || !hasUnsavedChanges) return;

        isSaving = true;
        hasUnsavedChanges = false;
        setIndicator('saving', 'Sauvegarde en cours...');

        try {
            const res = await fetch(autosaveUrl, {
                method: 'POST',
                body: getFormData(),
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (res.ok) {
                const json = await res.json();
                draftFormId = json.form_id;
                draftInput.value = draftFormId;
                setIndicator('saved', 'Brouillon sauvegardé à ' + json.saved_at);
            } else {
                setIndicator('error', 'Échec de la sauvegarde');
                hasUnsavedChanges = true;
            }
        } catch {
            setIndicator('error', 'Échec de la sauvegarde');
            hasUnsavedChanges = true;
        }
        isSaving = false;
    }

    function beaconSave() {
        if (!hasUnsavedChanges) return;
        navigator.sendBeacon(autosaveUrl, getFormData());
    }

    function scheduleAutosave() {
        hasUnsavedChanges = true;
        clearTimeout(autosaveTimeout);
        autosaveTimeout = setTimeout(doAutosave, 3000);
    }

    form.addEventListener('input', scheduleAutosave);
    form.addEventListener('change', scheduleAutosave);

    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'hidden') {
            clearTimeout(autosaveTimeout);
            beaconSave();
        }
    });

    window.addEventListener('beforeunload', () => {
        clearTimeout(autosaveTimeout);
        beaconSave();
    });

    setInterval(doAutosave, 30000);
})();
