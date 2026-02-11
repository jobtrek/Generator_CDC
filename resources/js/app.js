import './bootstrap';
import './markdown-editor';
import Alpine from 'alpinejs';

import { planningCalculatorEdit } from './planning-calculator';
import { cdcFormBuilder } from './form-builder';

window.planningCalculatorEdit = planningCalculatorEdit;
window.cdcFormBuilder = cdcFormBuilder;


window.Alpine = Alpine;

Alpine.start();
