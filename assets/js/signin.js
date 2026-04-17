function validateForm() {
    const form = document.getElementById('SignInForm');
    let isValid = true;

    // 1. Reset: Clear error text and red boxes
    document.querySelectorAll('span[id^="error-"]').forEach(span => span.innerText = "");
    form.querySelectorAll('.form-control').forEach(el => {
        el.classList.remove('input-error');
    });

    // 2. Target all required fields
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        const fieldName = field.getAttribute('name');
        const errorSpan = document.getElementById(`error-${fieldName}`);

        // STAGE 1: EMPTY CHECK
        if (!field.value.trim()) {
            if (errorSpan) errorSpan.innerText = " (Required)";
            field.classList.add('input-error');
            isValid = false;
            return; // STOP: Do not validate further if empty
        }
        if (fieldName === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(field.value)) {
                if (errorSpan) errorSpan.innerText = " (Invalid format)";
                field.classList.add('input-error');
                isValid = false;
            }
        }
         if (form.password.value.length < 8) {
            const passSpan = document.getElementById('error-password');
            if (passSpan) passSpan.innerText = " (Min 8 characters)";
            form.password.classList.add('input-error');
            isValid = false;
        }

    });

    return isValid; // Allow form submission if valid
}   