import './bootstrap';
import './markdown-editor';
import Alpine from 'alpinejs';

import { planningCalculatorEdit } from '../../public/js/planning-calculator.js';
import { cdcFormBuilder } from '../../public/js/form-builder.js';
import { projectHoursCalculator } from '../../public/js/project-hours-calculator.js';

window.planningCalculatorEdit = planningCalculatorEdit;
window.cdcFormBuilder = cdcFormBuilder;
window.projectHoursCalculator = projectHoursCalculator;

window.Alpine = Alpine;

Alpine.start();
