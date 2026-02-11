export function planningCalculatorEdit(oldData = {}) {
    const parseOldValue = (oldStr, defaultVal) => {
        if (!oldStr || oldStr === '') return defaultVal;
        const cleaned = String(oldStr).replace(/[H%]/g, '').trim();
        const parsed = parseInt(cleaned);
        return isNaN(parsed) ? defaultVal : parsed;
    };

    const defaultMode = oldData.planning_analyse?.includes('H') ? 'heures' : 'pourcentage';

    return {
        mode: defaultMode,
        analyse: parseOldValue(oldData.planning_analyse, defaultMode === 'heures' ? 14 : 15),
        implementation: parseOldValue(oldData.planning_implementation, defaultMode === 'heures' ? 45 : 50),
        tests: parseOldValue(oldData.planning_tests, defaultMode === 'heures' ? 18 : 20),
        documentation: parseOldValue(oldData.planning_documentation, defaultMode === 'heures' ? 13 : 15),

        get totalHeures() {
            const input = document.querySelector('input[name="nombre_heures"]');
            return parseInt(input?.value || 90);
        },

        get total() {
            return parseInt(this.analyse || 0) +
                parseInt(this.implementation || 0) +
                parseInt(this.tests || 0) +
                parseInt(this.documentation || 0);
        },

        heuresToPercent(heures) {
            return Math.round((heures / this.totalHeures) * 100);
        },

        percentToHeures(percent) {
            return Math.round((percent * this.totalHeures) / 100);
        },

        formatValue(val) {
            return val + (this.mode === 'heures' ? 'H' : '%');
        },

        init() {
            this.$watch('mode', (newMode, oldMode) => {
                if (newMode === 'pourcentage' && oldMode === 'heures') {
                    this.analyse = this.heuresToPercent(this.analyse);
                    this.implementation = this.heuresToPercent(this.implementation);
                    this.tests = this.heuresToPercent(this.tests);
                    this.documentation = this.heuresToPercent(this.documentation);
                } else if (newMode === 'heures' && oldMode === 'pourcentage') {
                    this.analyse = this.percentToHeures(this.analyse);
                    this.implementation = this.percentToHeures(this.implementation);
                    this.tests = this.percentToHeures(this.tests);
                    this.documentation = this.percentToHeures(this.documentation);
                }
            });

            this.$watch('analyse', (val) => {
                if (this.mode === 'heures' && val > this.totalHeures) {
                    this.analyse = this.totalHeures;
                } else if (this.mode === 'pourcentage' && val > 100) {
                    this.analyse = 100;
                }
            });

            this.$watch('implementation', (val) => {
                if (this.mode === 'heures' && val > this.totalHeures) {
                    this.implementation = this.totalHeures;
                } else if (this.mode === 'pourcentage' && val > 100) {
                    this.implementation = 100;
                }
            });

            this.$watch('tests', (val) => {
                if (this.mode === 'heures' && val > this.totalHeures) {
                    this.tests = this.totalHeures;
                } else if (this.mode === 'pourcentage' && val > 100) {
                    this.tests = 100;
                }
            });

            this.$watch('documentation', (val) => {
                if (this.mode === 'heures' && val > this.totalHeures) {
                    this.documentation = this.totalHeures;
                } else if (this.mode === 'pourcentage' && val > 100) {
                    this.documentation = 100;
                }
            });
        }
    };
}
