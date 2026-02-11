window.formatSwissPhone = function(input) {
    let digits = input.value.replace(/\D/g, '');

    if (digits.startsWith('41')) {
        digits = digits.substring(2);
    }digits = digits.substring(0, 9);

    let formatted = '+41';

    if (digits.length > 0) {
        formatted += ' ' + digits.substring(0, 2);
    }
    if (digits.length > 2) {
        formatted += ' ' + digits.substring(2, 5);
    }
    if (digits.length > 5) {
        formatted += ' ' + digits.substring(5, 7);
    }
    if (digits.length > 7) {
        formatted += ' ' + digits.substring(7, 9);
    }
    input.value = formatted;
};
