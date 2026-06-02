export function projectHoursCalculator(initData = {}) {
    const DAY_MAP = { lundi: 1, mardi: 2, mercredi: 3, jeudi: 4, vendredi: 5 };

    return {
        dateDebut: initData.dateDebut || '',
        dateFin: initData.dateFin || '',
        heureMatinDebut: initData.heureMatinDebut || '08:30',
        heureMatinFin: initData.heureMatinFin || '12:30',
        heureApremDebut: initData.heureApremDebut || '13:30',
        heureApremFin: initData.heureApremFin || '17:30',
        pauseMatinDebut: initData.pauseMatinDebut || '10:30',
        pauseMatinFin: initData.pauseMatinFin || '10:45',
        pauseApremDebut: initData.pauseApremDebut || '15:00',
        pauseApremFin: initData.pauseApremFin || '15:15',
        selectedDays: [],
        joursFeries: initData.joursFeries || [],
        joursCoursRecuperer: initData.joursCoursRecuperer || 0,
        newFerieDate: '',

        init() {
            this.updateSelectedDays();

            document.querySelectorAll('[name="jours_ecole[]"]').forEach(cb => {
                cb.addEventListener('change', () => this.updateSelectedDays());
            });

            this.$watch('totalHeuresCalculees', (val) => {
                if (!this.dateDebut || !this.dateFin) return;
                const input = document.querySelector('input[name="nombre_heures"]');
                if (input) {
                    input.value = val;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });

            this.$nextTick(() => {
                if (this.dateDebut && this.dateFin) {
                    const input = document.querySelector('input[name="nombre_heures"]');
                    if (input) {
                        input.value = this.totalHeuresCalculees;
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }
            });
        },

        updateSelectedDays() {
            this.selectedDays = Array.from(
                document.querySelectorAll('[name="jours_ecole[]"]:checked')
            ).map(cb => cb.value);
        },

        timeToMin(t) {
            if (!t) return 0;
            const parts = t.split(':').map(Number);
            return (parts[0] || 0) * 60 + (parts[1] || 0);
        },

        get heuresParJour() {
            let matin = (this.timeToMin(this.heureMatinFin) - this.timeToMin(this.heureMatinDebut)) / 60;
            let aprem = (this.timeToMin(this.heureApremFin) - this.timeToMin(this.heureApremDebut)) / 60;
            const pMD = this.timeToMin(this.pauseMatinDebut);
            const pMF = this.timeToMin(this.pauseMatinFin);
            const pAD = this.timeToMin(this.pauseApremDebut);
            const pAF = this.timeToMin(this.pauseApremFin);
            if (pMF > pMD) matin -= (pMF - pMD) / 60;
            if (pAF > pAD) aprem -= (pAF - pAD) / 60;
            return Math.max(0, Math.round((matin + aprem) * 100) / 100);
        },

        get joursOuvrablesBruts() {
            if (!this.dateDebut || !this.dateFin) return 0;
            const start = new Date(this.dateDebut);
            const end = new Date(this.dateFin);
            if (isNaN(start) || isNaN(end) || end < start) return 0;
            let count = 0;
            const cur = new Date(start);
            while (cur <= end) {
                const d = cur.getDay();
                if (d >= 1 && d <= 5) count++;
                cur.setDate(cur.getDate() + 1);
            }
            return count;
        },

        get joursEcoleTotal() {
            if (!this.dateDebut || !this.dateFin || !this.selectedDays.length) return 0;
            const start = new Date(this.dateDebut);
            const end = new Date(this.dateFin);
            if (isNaN(start) || isNaN(end) || end < start) return 0;
            const nums = this.selectedDays.map(d => DAY_MAP[d]).filter(Boolean);
            let count = 0;
            const cur = new Date(start);
            while (cur <= end) {
                if (nums.includes(cur.getDay())) count++;
                cur.setDate(cur.getDate() + 1);
            }
            return count;
        },

        get joursFeriesEffectifs() {
            if (!this.dateDebut || !this.dateFin || !this.joursFeries.length) return 0;
            const start = new Date(this.dateDebut);
            const end = new Date(this.dateFin);
            const schoolNums = this.selectedDays.map(d => DAY_MAP[d]).filter(Boolean);
            return this.joursFeries.filter(ds => {
                const d = new Date(ds);
                if (isNaN(d)) return false;
                const dow = d.getDay();
                return d >= start && d <= end && dow >= 1 && dow <= 5 && !schoolNums.includes(dow);
            }).length;
        },

        get joursTpiEffectifs() {
            return Math.max(0,
                this.joursOuvrablesBruts
                - this.joursEcoleTotal
                - this.joursFeriesEffectifs
                - (parseInt(this.joursCoursRecuperer) || 0)
            );
        },

        get totalHeuresCalculees() {
            return Math.min(90, Math.round(this.joursTpiEffectifs * this.heuresParJour));
        },

        addFerie() {
            if (this.newFerieDate && !this.joursFeries.includes(this.newFerieDate)) {
                this.joursFeries = [...this.joursFeries, this.newFerieDate].sort();
                this.newFerieDate = '';
            }
        },

        removeFerie(date) {
            this.joursFeries = this.joursFeries.filter(d => d !== date);
        },

        formatDate(ds) {
            if (!ds) return '';
            const [y, m, d] = ds.split('-');
            return `${d}.${m}.${y}`;
        },
    };
}
