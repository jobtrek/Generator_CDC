import './bootstrap';
import './phone-formatter';
import './cdc-progress';
import Alpine from 'alpinejs';
import { marked } from 'marked';
import DOMPurify from 'dompurify';

import { planningCalculatorEdit } from '../../public/js/planning-calculator.js';
import { cdcFormBuilder } from '../../public/js/form-builder.js';
import { projectHoursCalculator } from '../../public/js/project-hours-calculator.js';

window.marked = marked;
window.DOMPurify = DOMPurify;
window.planningCalculatorEdit = planningCalculatorEdit;
window.cdcFormBuilder = cdcFormBuilder;
window.projectHoursCalculator = projectHoursCalculator;

window.Alpine = Alpine;

Alpine.start();
