(function () {
    const form = document.getElementById('cdc-form');
    if (!form) return;

    const SECTIONS = [
        {
            id: 'section-1',
            fields: [
                'candidat_nom', 'candidat_prenom', 'candidat_email', 'candidat_telephone',
                'lieu_travail',
                { name: 'orientation', type: 'radio' },
                'chef_projet_nom', 'chef_projet_prenom', 'chef_projet_email', 'chef_projet_telephone',
                'expert1_nom', 'expert1_prenom', 'expert1_email', 'expert1_telephone',
                'expert2_nom', 'expert2_prenom', 'expert2_email', 'expert2_telephone',
                'date_debut', 'date_fin',
                'heure_matin_debut', 'heure_matin_fin',
                'heure_aprem_debut', 'heure_aprem_fin',
            ]
        },
        { id: 'section-2', fields: ['procedure'] },
        { id: 'section-3', fields: ['titre_projet'] },
        { id: 'section-4', fields: ['materiel_logiciel'] },
        { id: 'section-5', fields: ['prerequis'] },
        { id: 'section-6', fields: ['descriptif_projet'] },
        { id: 'section-7', fields: ['livrables'] },
    ];

    function isFieldFilled(field) {
        if (typeof field === 'object' && field.type === 'radio') {
            return !!form.querySelector(`[name="${field.name}"]:checked`);
        }
        const name = typeof field === 'string' ? field : field.name;
        const el = form.elements[name];
        if (!el) return false;
        const val = el.value.trim();
        if (name.includes('telephone') && val === '+41') return false;
        return val !== '';
    }

    function updateProgress() {
        let total  = 0;
        let filled = 0;
        SECTIONS.forEach(s => {
            total  += s.fields.length;
            filled += s.fields.filter(f => isFieldFilled(f)).length;
        });
        const pct = total > 0 ? Math.round(filled / total * 100) : 0;
        document.getElementById('progress-fill').style.width = pct + '%';
    }

    form.addEventListener('input', updateProgress);
    form.addEventListener('change', updateProgress);
    updateProgress();
})();
