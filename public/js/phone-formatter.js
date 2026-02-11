function formatSwissPhone(input) {
    let v = input.value.replace(/[^0-9\+]/g, '');

    if (!v.startsWith('+41')) {
        v = '+41';
    }
    let digits = v.substring(3).replace(/\D/g, '').substring(0, 9);

    let formatted = '+41';
    if (digits.length > 0) formatted += ' ' + digits.substring(0, 2);
    if (digits.length > 2) formatted += ' ' + digits.substring(2, 5);
    if (digits.length > 5) formatted += ' ' + digits.substring(5, 7);
    if (digits.length > 7) formatted += ' ' + digits.substring(7, 9);
    input.value = formatted;
}
