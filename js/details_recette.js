
function onAddClick() {
    const currentUrl = window.location.href;

    const url = new URL(currentUrl);
    const recetteId = url.searchParams.get("recette");

    console.log(recetteId)
    addToFavorites(recetteId)
}

function addToFavorites(recetteId) {
    fetch('../../php/favories.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ recette_id: recetteId }),
    })
    .then(response => response.json())
    .then(data => {
        //alert('Recette ajoutée aux favoris !');
        console.log('Success:', data);
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}



function onAddcommentClick() {
    console.log('bouraz')
    const currentUrl = window.location.href;
    const url = new URL(currentUrl);
    const recetteId = url.searchParams.get("recette");
    const comment = document.getElementById('comment').value;

    addTocomments(recetteId, comment);
}

function addTocomments(recetteId, comment) {
    fetch('../../php/commentaires.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ recette_id: recetteId, comment: comment }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            //alert('Commentaire ajouté !');
            location.reload(); // Recharge la page pour afficher le nouveau commentaire
        } else {
            alert('Erreur lors de l\'ajout du commentaire : ' + data.error);
        }
        console.log('Success:', data);
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}
let star = document.querySelectorAll('input');
let showValue = document.querySelector('#rating-value');

for (let i = 0; i < star.length; i++) {
	star[i].addEventListener('click', function() {
		i = this.value;

		showValue.innerHTML = i + " out of 5";
	});
}
function onRatingSubmit() {
    const currentUrl = window.location.href;
    const url = new URL(currentUrl);
    const recetteId = url.searchParams.get("recette");

    const ratingValue = document.querySelector('input[name="rating"]:checked').value;

    submitRating(recetteId, ratingValue);
}

function submitRating(recetteId, ratingValue) {
    fetch('../../php/evaluation.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ recette_id: recetteId, rating: ratingValue }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Rating submitted!');
            location.reload(); // Reload to update the displayed average rating
        } else {
            alert('Error submitting rating: ' + data.error);
        }
        console.log('Success:', data);
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}
