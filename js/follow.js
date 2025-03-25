function onfollowClick(chefId, action) {
    fetch('../../php/follow.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ chef_id: chefId, action: action }),
    })
    .then(response => response.json())
    .then(data => {
        console.log('Success:', data);
        // Mettre à jour le texte du bouton après l'action
        updateFollowButton(action);
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}

function updateFollowButton(action) {
    const followButton = document.querySelector('.follow-btn[data-chef-id="' + chefId + '"]');
    if (action === 'follow') {
        followButton.textContent = 'Suivi(e)';
        followButton.setAttribute('onclick', `onfollowClick(${chefId}, 'unfollow')`);
    } else {
        followButton.textContent = 'Suivre';
        followButton.setAttribute('onclick', `onfollowClick(${chefId}, 'follow')`);
    }
}


function OnarticleClick(articleId) {
    window.location.replace('article_deatails.php?article=' + articleId);
}