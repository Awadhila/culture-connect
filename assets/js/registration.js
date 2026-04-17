/**
 * Validation Logic for CultureConnect
 */

function toggleSmeFields() {
    const checkBox = document.getElementById("isSme");
    const smeSection = document.getElementById("smeFields");
    // Match these IDs with your signup.php input IDs
    const smeName = document.getElementById("sme_name");
    const smeEmail = document.getElementById("sme_email");

    if (checkBox.checked) {
        smeSection.style.display = "block";
        smeName.setAttribute("required", "");
        smeEmail.setAttribute("required", "");
    } else {
        smeSection.style.display = "none";
        smeName.removeAttribute("required");
        smeEmail.removeAttribute("required");
        smeName.value = "";
        smeEmail.value = "";
    }
}

async function validateForm() {
    const form = document.getElementById('registrationForm');
    let isValid = true;

    // 1. Reset: Clear error text and red boxes
    document.querySelectorAll('span[id^="error-"]').forEach(span => span.innerText = "");
    form.querySelectorAll('.form-control, .form-select').forEach(el => {
        el.classList.remove('input-error');
    });

    // 2. Target all currently required fields (includes SME if checked)
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        const fieldName = field.getAttribute('name');
        const errorSpan = document.getElementById(`error-${fieldName}`);

        // STAGE 1: EMPTY CHECK
        if (!field.value.trim() && field.type !== 'radio') {
            if (errorSpan) errorSpan.innerText = " (Required)";
            field.classList.add('input-error');
            isValid = false;
            return; // STOP: Do not validate further if empty
        }

        // Handle Gender (Radio)
        if (field.type === 'radio') {
            const radioChecked = form.querySelector(`input[name="${fieldName}"]:checked`);
            if (!radioChecked) {
                const genderSpan = document.getElementById('error-gender');
                if (genderSpan) genderSpan.innerText = " (Select one)";
                isValid = false;
                return;
            }
        }

        // STAGE 2: SPECIFIC CHECKS (Only runs if Stage 1 passed)
        
        // Email Validation (User and SME)
        if (fieldName === 'email' || fieldName === 'sme_email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(field.value)) {
                if (errorSpan) errorSpan.innerText = " (Invalid format)";
                field.classList.add('input-error');
                isValid = false;
            }
        }

        // Date of Birth (Not more than 100 years ago)
        if (fieldName === 'dob') {
            const birthDate = new Date(field.value);
            const today = new Date();
            const minDate = new Date();
            minDate.setFullYear(today.getFullYear() - 100);

            if (birthDate < minDate) {
                if (errorSpan) errorSpan.innerText = " (Max 100 years)";
                field.classList.add('input-error');
                isValid = false;
            } else if (birthDate > today) {
                if (errorSpan) errorSpan.innerText = " (Future date)";
                field.classList.add('input-error');
                isValid = false;
            }
        }

        // Alphabetic Check (Names and SME Name)
        const alphaFields = ['first_name', 'last_name', 'sme_name'];
        if (alphaFields.includes(fieldName) && !/^[A-Za-z\s]+$/.test(field.value)) {
            if (errorSpan) errorSpan.innerText = " (Alphabets only)";
            field.classList.add('input-error');
            isValid = false;
        }
    });
    console.log("Stage 1 & 2 Validation Complete. Valid so far?", isValid);
    // 3. Password Match Check
    if (form.password.value && form.confirm_password.value) {
        if (form.password.value !== form.confirm_password.value) {
            const confirmSpan = document.getElementById('error-confirm_password');
            if (confirmSpan) confirmSpan.innerText = " (Passwords do not match)";
            form.confirm_password.classList.add('input-error');
            isValid = false;
        } else if (form.password.value.length < 8) {
            const passSpan = document.getElementById('error-password');
            if (passSpan) passSpan.innerText = " (Min 8 characters)";
            form.password.classList.add('input-error');
            isValid = false;
        }
    }

// 4. NEW: BACK-END UNIQUE EMAIL CHECK
// NEW: Unique Email Check
    const emailInput = form.querySelector('input[name="email"]');
    const emailValue = emailInput.value.trim();
    if (emailValue !== "") { // Only check if email is not empty and previous validations passed
        try {
            const response = await fetch('processes/check-email.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'email=' + encodeURIComponent(emailValue)
                
            });
            const data = await response.json();
            if (data.exists) {
                const emailSpan = document.getElementById('error-email');
                if (emailSpan) emailSpan.innerText = " (Email already exists)";
                emailInput.classList.add('input-error');
                isValid = false;
            }
        } catch (error) {
            console.error("Database check failed", error);
        }
    }

    // 5. Final Decision
    if (!isValid) {
        window.scrollTo(0, 0);
        return false;
    }
    form.submit();
}