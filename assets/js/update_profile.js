document.querySelectorAll('.edit-trigger').forEach(button => {
    button.addEventListener('click', function() {
        const row = this.closest('.row');
        const input = row.querySelector('input[type="text"], input[type="date"], select, textarea, .gender-inputs');
        const span = row.querySelector('.field-text');
        const saveBtn = document.getElementById('saveChanges');

        if (input) {
            input.classList.remove('d-none');
            span.classList.add('d-none');
            saveBtn.classList.remove('d-none');
            this.innerText = "Editing...";
            this.disabled = true;
        }
    });
});

// Preview image before upload
document.getElementById('profileInput').addEventListener('change', function(e) {
    const reader = new FileReader();
    reader.onload = function(event) {
        document.getElementById('profilePreview').src = event.target.result;
        document.getElementById('saveChanges').classList.remove('d-none');
    };
    reader.readAsDataURL(e.target.files[0]);
});