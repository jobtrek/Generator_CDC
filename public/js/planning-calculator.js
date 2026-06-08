export function planningCalculatorEdit(oldData = {}) {
    const parseOldValue = (oldStr, defaultVal) => {
        if (!oldStr || oldStr === '') return defaultVal;
        const cleaned = String(oldStr).replace(/[Hh%]/g, '').trim();
        const parsed = parseInt(cleaned);
        return isNaN(parsed) ? defaultVal : parsed;
    };

    const detectMode = () => {
        if (oldData.planning_analyse) {
            return String(oldData.planning_analyse).toUpperCase().includes('H') ? 'heures' : 'pourcentage';
        }
        return 'pourcentage';
    };

    return {
        mode: detectMode(),
        totalHeures: parseInt(oldData.total_heures) || 90,

        analyse: 15,
        implementation: 50,
        tests: 20,
        documentation: 15,

        get total() {
            return (parseInt(this.analyse) || 0) +
                (parseInt(this.implementation) || 0) +
                (parseInt(this.tests) || 0) +
                (parseInt(this.documentation) || 0);
        },

        get totalPercent() {
            if (this.totalHeures === 0) return 0;
            return this.mode === 'heures'
                ? Math.round((this.total / this.totalHeures) * 100)
                : this.total;
        },

        get isValid() {
            if (this.mode === 'pourcentage') {
                return this.total === 100;
            } else {
                return this.total <= this.totalHeures;
            }
        },

        get validationMessage() {
            if (this.isValid) return '✓ Planning valide';

            if (this.mode === 'pourcentage') {
                return `⚠ Le total doit être 100% (actuellement ${this.total}%)`;
            } else {
                return `⚠ Le total ne doit pas dépasser ${this.totalHeures}h (actuellement ${this.total}h)`;
            }
        },

        formatValue(val) {
            return val + (this.mode === 'heures' ? 'H' : '%');
        },

        percentToHeures(percent) {
            return Math.round((percent * this.totalHeures) / 100);
        },

        normalizeSum(targetTotal) {
            const diff = targetTotal - this.total;
            if (diff === 0) return;
            const keys = ['analyse', 'implementation', 'tests', 'documentation'];
            const largest = keys.reduce((a, b) => this[a] >= this[b] ? a : b);
            this[largest] = (this[largest] || 0) + diff;
        },

        _scaleFields(oldMax, newMax) {
            const wasComplete = this.total === oldMax;
            ['analyse', 'implementation', 'tests', 'documentation'].forEach(k => {
                this[k] = Math.round((this[k] / oldMax) * newMax);
            });
            if (wasComplete) this.normalizeSum(newMax);
        },

        switchMode(newMode) {
            if (newMode === this.mode) return;

            const oldMax = this.mode === 'heures' ? this.totalHeures : 100;
            const newMax = newMode === 'heures' ? this.totalHeures : 100;

            if (oldMax > 0) this._scaleFields(oldMax, newMax);

            this.mode = newMode;
        },

        getMax() {
            return this.mode === 'heures' ? this.totalHeures : 100;
        },

        clampValue(val) {
            const max = this.getMax();
            const num = parseInt(val) || 0;
            if (num < 0) return 0;
            if (num > max) return max;
            return num;
        },

        autoAdjustForPercent() {
            if (this.mode !== 'pourcentage' || this.total <= 100) return;
            let excess = this.total - 100;
            const keys = ['documentation', 'tests', 'implementation', 'analyse'];
            for (let key of keys) {
                if (this[key] > 0 && excess > 0) {
                    const reduction = Math.min(this[key], excess);
                    this[key] -= reduction;
                    excess -= reduction;
                }
            }
        },

        init() {
            if (oldData.planning_analyse) {
                this.analyse = parseOldValue(oldData.planning_analyse, 15);
                this.implementation = parseOldValue(oldData.planning_implementation, 50);
                this.tests = parseOldValue(oldData.planning_tests, 20);
                this.documentation = parseOldValue(oldData.planning_documentation, 15);
            }

            const hoursInput = document.querySelector('input[name="nombre_heures"]');
            if (hoursInput) {

                hoursInput.addEventListener('change', (e) => {
                    const newTotal = parseInt(e.target.value) || 90;
                    const oldTotal = this.totalHeures;

                    if (this.mode === 'heures' && oldTotal > 0) {
                        this._scaleFields(oldTotal, newTotal);
                    }
                    this.totalHeures = newTotal;
                });
            }
        }
    };
}
