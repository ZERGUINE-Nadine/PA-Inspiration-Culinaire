$(document).ready(function() {
    // Fonction pour rafraîchir les messages
    function refreshMessages() {
        $.ajax({
            url: '../../php/recup_messages.php', // Fichier PHP pour récupérer les messages
            success: function(data) {
                $('.messages').html(data); // Remplace le contenu des messages
            }
        });
    }

    // Rafraîchir les messages toutes les 5 secondes
    setInterval(refreshMessages, 5000);

    // Envoi du formulaire via AJAX
    $('.message-form').on('submit', function(e) {
        e.preventDefault(); // Empêcher l'envoi normal du formulaire
        var message = $('#message').val();
        
        $.ajax({
            url: '../../php/envoi_message.php',
            method: 'POST',
            data: { message: message },
            success: function(response) {
                $('#message').val(''); // Réinitialiser le champ de texte
                refreshMessages(); // Rafraîchir les messages immédiatement
            }
        });
    });
});
