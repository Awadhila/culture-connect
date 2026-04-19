document.getElementById('voteBtn')?.addEventListener('click', function() {
    const productID = this.getAttribute('data-id');
    const itemType = this.getAttribute('data-type');
    const btn = this;

    // Use FormData to send the POST request
    const formData = new FormData();
    formData.append('product_id', productID);

    fetch('processes/cast_vote.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            btn.innerText = itemType + ' VOTED';
            btn.classList.replace('btn-dark', 'btn-success');
            btn.disabled = true;
            alert(data.message);
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});