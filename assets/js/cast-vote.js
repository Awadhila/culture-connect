function castVote(itemId, value) {
    const formData = new FormData();
    formData.append('product_id', itemId);
    formData.append('vote_value', value);

    fetch('processes/cast_vote.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            location.reload(); 
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}