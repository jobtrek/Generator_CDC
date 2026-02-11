export function cdcFormBuilder() {
    return {
        fields: [],
        tempIdCounter: Date.now(),

        addField() {
            this.fields.push({
                tempId: this.tempIdCounter++,
                name: '',
                label: '',
                field_type_id: '1',
                value: ''
            });
        },

        removeField(index) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce champ ?')) {
                this.fields.splice(index, 1);
            }
        }
    };
}
