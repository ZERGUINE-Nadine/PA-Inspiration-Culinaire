document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('add-to-favorites').addEventListener('click', function(event) {
        event.preventDefault();
        addToFavorites(article_id);
    });
    
});

function onAddClick() {
    addToFavorites(articleId)
}

function addToFavorites() {
    const currentUrl = window.location.href;

    const url = new URL(currentUrl);
    const articleId = url.searchParams.get("article");
    fetch('../../php/favories.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ article_id: articleId }),
    })
    .then(response => response.json())
    .then(data => {
            alert('Article ajouté aux favoris !');
            console.log('Success:', data);
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('add-to-comments').addEventListener('click', function(event) {
        event.preventDefault();
        onAddcommentClick();
    });
});

function onAddCommentClick() {
    const currentUrl = window.location.href;
    const url = new URL(currentUrl);
    const articleId = url.searchParams.get("article");
    const comment = document.getElementById('comment').value;

    addTocomments(articleId, comment);

}

function addTocomments(articleId, comment) {
    fetch('../../php/commentaires.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ article_id: articleId, comment: comment }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Commentaire ajouté !');
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
    const articleId = url.searchParams.get("article");

    const ratingValue = document.querySelector('input[name="rating"]:checked').value;

    submitRating(articleId, ratingValue);
}

function submitRating(articleId, ratingValue) {
    fetch('../../php/evaluation.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ article_id: articleId, rating: ratingValue, type: 'article'}),
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
