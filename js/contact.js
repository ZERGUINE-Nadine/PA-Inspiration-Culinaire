async function sendEmail() {
    let name = document.getElementById('name').value.trim();
    let email = document.getElementById('email').value.trim();
    let message = document.getElementById('message').value.trim();

    // Récupération des éléments d'erreur
    let nameError = document.getElementById('nameError');
    let emailError = document.getElementById('emailError');
    let messageError = document.getElementById('messageError');

    // Vérification si les éléments existent avant de définir textContent
    if (nameError) {
        nameError.textContent = '';
    } else {
        console.error('Element with ID "nameError" not found.');
    }

    if (emailError) {
        emailError.textContent = '';
    } 

    if (messageError) {
        messageError.textContent = '';
    } 

    // Validation des champs
    let isValid = true;

    if (name === '') {
        nameError.textContent = 'Veuillez entrer votre pseudo nom.';
        isValid = false;
    }

    if (email === '') {
        emailError.textContent = 'Veuillez entrer votre adresse email.';
        isValid = false;
    } else if (!validateEmail(email)) {
        emailError.textContent = 'Adresse mail invalide';
        isValid = false;
    }

    if (message === '') {
        messageError.textContent = 'Veuillez entrer votre message.';
        isValid = false;
    }

    if (!isValid) return;

    data = {
        name: name,
        email: email,
        message: message
    }

    const response = await sendMessag(data);
    console.log(await response.json())
    
}

async function sendMessag(code) {
    let url = "../php/message.php";
    const response = fetch (url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    });
    return response; 
 }

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}

// Pour afficher des messages
let toastBox = document.getElementById('toastBox');

function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `custom-toast ${type}`;
    toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
    toastBox.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 5000);
}
