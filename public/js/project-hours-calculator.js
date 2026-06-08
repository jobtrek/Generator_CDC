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

            this.$watch('totalHeuresCalculees', () => this._syncHoursInput());
            this.$nextTick(() => this._syncHoursInput());
        },

        _syncHoursInput() {
            if (!this.dateDebut || !this.dateFin) return;
            const input = document.querySelector('input[name="nombre_heures"]');
            if (input) {
                input.value = this.totalHeuresCalculees;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        },

        updateSelectedDays() {
            this.selectedDays = Array.from(
                document.querySelectorAll('[name="jours_ecole[]"]:checked')
            ).map(cb => cb.value);
        },

        timeToMin(t) {
            if (!t) return 0;
            const [h, m] = t.split(':').map(Number);
            return (h || 0) * 60 + (m || 0);
        },

        get minutesParJour() {
            const matin = this.timeToMin(this.heureMatinFin) - this.timeToMin(this.heureMatinDebut);
            const aprem = this.timeToMin(this.heureApremFin) - this.timeToMin(this.heureApremDebut);
            const pauseMatin = Math.max(0, this.timeToMin(this.pauseMatinFin) - this.timeToMin(this.pauseMatinDebut));
            const pauseAprem = Math.max(0, this.timeToMin(this.pauseApremFin) - this.timeToMin(this.pauseApremDebut));
            return Math.max(0, matin + aprem - pauseMatin - pauseAprem);
        },

        get heuresParJour() {
            return this.minutesParJour / 60;
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

        get totalMinutesCalculees() {
            return Math.min(90 * 60, this.joursTpiEffectifs * this.minutesParJour);
        },

        get totalHeuresCalculees() {
            return Math.floor(this.totalMinutesCalculees / 60);
        },

        get totalMinutesRest() {
            return this.totalMinutesCalculees % 60;
        },

        get totalHeuresFormatted() {
            const m = this.totalMinutesRest;
            return m > 0
                ? `${this.totalHeuresCalculees}h${String(m).padStart(2, '0')}`
                : `${this.totalHeuresCalculees}h`;
        },

        get heuresParJourFormatted() {
            const min = this.minutesParJour;
            const h = Math.floor(min / 60);
            const m = min % 60;
            return m > 0 ? `${h}h${String(m).padStart(2, '0')}` : `${h}h`;
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
