document.addEventListener('DOMContentLoaded', (event) => {
    const toggleButton = document.getElementById('dark-mode-toggle');
    
    // Vérifier la préférence de l'utilisateur lors du chargement de la page
    if (localStorage.getItem('darkMode') === 'enabled') {
      document.body.classList.add('dark-mode');
    }
    
    toggleButton.addEventListener('click', function() {
      document.body.classList.toggle('dark-mode');
      
      // Enregistrer la préférence de l'utilisateur dans le localStorage
      if (document.body.classList.contains('dark-mode')) {
        localStorage.setItem('darkMode', 'enabled');
      } else {
        localStorage.setItem('darkMode', 'disabled');
      }
    });
  });
  