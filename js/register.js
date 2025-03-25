
function isValidPassword(password) {
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    return passwordPattern.test(password);
    return true;
}

async function onRegisterClick() {
    let name = document.getElementById('name').value;
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;
    let confirmPassword = document.getElementById('confirmPassword').value;

    let nameError = document.getElementById('nameError');
    let emailError = document.getElementById('emailError');
    let passwordError = document.getElementById('passwordError');
    let confirmPasswordError = document.getElementById('confirmPasswordError');
    nameError.textContent = '';
    emailError.textContent = '';
    passwordError.textContent = '';
    confirmPasswordError.textContent = '';

    // validation du formulaire
    let isValid = true;

    if (name === '') {
        nameError.textContent = 'Veuillez entrer votre pseudo nom.';
        isValid = false;
    }

    if (email === '') {
        emailError.textContent = 'Veuillez entrer votre adresse email.';
        isValid = false;
    }

    if (password === '') {
        passwordError.textContent = 'Veuillez entrer votre mot de passe.';
        isValid = false;
    } else if (!isValidPassword(password)) {
        passwordError.textContent = 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.';
        isValid = false;
    }

    if (confirmPassword === '') {
        confirmPasswordError.textContent = 'Veuillez confirmer votre mot de passe.';
        isValid = false;
    } else if (password !== confirmPassword) {
        confirmPasswordError.textContent = 'Les mots de passe ne sont pas identiques.';
        isValid = false;
    }

    // si validé on construit la data et on envoie la requête POST
    if (isValid) {
        let data = {
            name: name,
            email: email,
            password: password
        };

        const response = await register(data);
        if (!response.ok){
            const error = await response.json()
            const errorMessage = error.message;
            showToast('error', errorMessage);
            return;
        } else {
            const successMessage = await response.json()
            showToast('success', successMessage.message);
             window.location.replace('../pages/includes/code_verification.php?email=' + email);
        }
    }
}

// construction et envoi de la requête
async function register(data) {
    const url = '../php/auth/user_register.php';
    console.log(url)
    let response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    });

    return response;
}


let taostBox = document.getElementById('toastBox');

function showToast(type, message) {

    const toast = document.createElement('div') 
    if (type == 'success') {
        toast.innerHTML = '<i class="fa-solid fa-circle-check"></i>'+ ' ' + message ;
        toast.setAttribute('class', 'custom-toast success')
    }

    if (type == 'error') {
        toast.innerHTML = '<i class="fa-solid fa-xmark"></i>' + '  ' + message ;
        toast.setAttribute('class', 'custom-toast error')
    }
    setTimeout(() => {
        toast.remove()
    }, 5000)
    taostBox.appendChild(toast);    

}
