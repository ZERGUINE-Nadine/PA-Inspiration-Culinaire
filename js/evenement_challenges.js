function OnEventClick(url) {
    window.location.href = url;
}
function OnChallengeClick(challengeId) {
    window.location.replace('details_challenge.php?challenge=' + challengeId);
}


function inscrireAuChallenge(id) {
    fetch('../../php/inscription.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + id
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Inscription réussie!');
            location.reload(); // Recharger la page pour mettre à jour le nombre d'inscrits
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => console.error('Erreur:', error));
}

