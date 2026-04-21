export function projectHoursCalculator() {
    return {
        pauseMatinDebut: '',
        pauseMatinFin: '',
        pauseApremDebut: '',
        pauseApremFin: '',
        selectedDays: [],

        init() {
            this.setupEventListeners();
            this.calculateHours();
        },

        setupEventListeners() {
            const fields = [
                'date_debut', 'date_fin',
                'heure_matin_debut', 'heure_matin_fin',
                'heure_aprem_debut', 'heure_aprem_fin',
                'pause_matin_debut', 'pause_matin_fin',
                'pause_aprem_debut', 'pause_aprem_fin'
            ];

            fields.forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    input.addEventListener('change', () => this.calculateHours());
                    input.addEventListener('input', () => this.calculateHours());
                }
            });

            const daysInputs = document.querySelectorAll('[name="jours_ecole[]"]');
            daysInputs.forEach(input => {
                input.addEventListener('change', () => {
                    this.updateSelectedDays();
                    this.calculateHours();
                });
            });

            const hiddenDaysInput = document.querySelector('input[name="jours_ecole_hidden"]');
            if (hiddenDaysInput && hiddenDaysInput.value) {
                const savedDays = JSON.parse(hiddenDaysInput.value);
                savedDays.forEach(day => {
                    const checkbox = document.querySelector(`[name="jours_ecole[]"][value="${day}"]`);
                    if (checkbox) checkbox.checked = true;
                });
                this.updateSelectedDays();
            }
        },

        updateSelectedDays() {
            this.selectedDays = Array.from(document.querySelectorAll('[name="jours_ecole[]"]:checked'))
                .map(cb => cb.value);
        },

        getWorkingHoursPerDay() {
            const matinDebut = this.timeToMinutes(document.querySelector('[name="heure_matin_debut"]')?.value || '08:30');
            const matinFin = this.timeToMinutes(document.querySelector('[name="heure_matin_fin"]')?.value || '12:30');
            const apremDebut = this.timeToMinutes(document.querySelector('[name="heure_aprem_debut"]')?.value || '13:30');
            const apremFin = this.timeToMinutes(document.querySelector('[name="heure_aprem_fin"]')?.value || '17:30');

            const pauseMatinDebut = this.timeToMinutes(document.querySelector('[name="pause_matin_debut"]')?.value || '');
            const pauseMatinFin = this.timeToMinutes(document.querySelector('[name="pause_matin_fin"]')?.value || '');
            const pauseApremDebut = this.timeToMinutes(document.querySelector('[name="pause_aprem_debut"]')?.value || '');
            const pauseApremFin = this.timeToMinutes(document.querySelector('[name="pause_aprem_fin"]')?.value || '');

            let matinHours = (matinFin - matinDebut) / 60;
            let apremHours = (apremFin - apremDebut) / 60;

            if (pauseMatinFin > pauseMatinDebut) {
                matinHours -= (pauseMatinFin - pauseMatinDebut) / 60;
            }

            if (pauseApremFin > pauseApremDebut) {
                apremHours -= (pauseApremFin - pauseApremDebut) / 60;
            }

            return Math.max(0, matinHours + apremHours);
        },

        getNumberOfSchoolDays() {
            const dateDebut = document.querySelector('[name="date_debut"]')?.value;
            const dateFin = document.querySelector('[name="date_fin"]')?.value;

            if (!dateDebut || !dateFin || this.selectedDays.length === 0) {
                return 0;
            }

            const dayMap = {
                '0': 0, 'sun': 0, 'dimanche': 0,
                '1': 1, 'mon': 1, 'lundi': 1,
                '2': 2, 'tue': 2, 'mardi': 2,
                '3': 3, 'wed': 3, 'mercredi': 3,
                '4': 4, 'thu': 4, 'jeudi': 4,
                '5': 5, 'fri': 5, 'vendredi': 5,
                '6': 6, 'sat': 6, 'samedi': 6
            };

            const validDayNumbers = this.selectedDays.map(d => dayMap[d] ?? parseInt(d)).filter(d => !isNaN(d));
            if (validDayNumbers.length === 0) return 0;

            const start = new Date(dateDebut);
            const end = new Date(dateFin);
            let count = 0;
            const current = new Date(start);

            while (current <= end) {
                if (validDayNumbers.includes(current.getDay())) {
                    count++;
                }
                current.setDate(current.getDate() + 1);
            }

            return count;
        },

        calculateHours() {
            const schoolDays = this.getNumberOfSchoolDays();
            const hoursPerDay = this.getWorkingHoursPerDay();
            const totalHours = Math.round(schoolDays * hoursPerDay);

            const totalHoursInput = document.querySelector('input[name="nombre_heures"]');
            if (totalHoursInput) {
                totalHoursInput.value = totalHours;

                const event = new Event('change', { bubbles: true });
                totalHoursInput.dispatchEvent(event);
            }

            const display = document.querySelector('#calculated-hours-display');
            if (display) {
                display.textContent = `${totalHours}h (${schoolDays} jours × ${hoursPerDay.toFixed(1)}h/jour)`;
            }
        },

        timeToMinutes(timeStr) {
            if (!timeStr) return 0;
            const [hours, minutes] = timeStr.split(':').map(Number);
            return (hours || 0) * 60 + (minutes || 0);
        }
    };
}
