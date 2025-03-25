async function onLoginBtnClick() {
    // récuperer les valeur des champs de login 
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;

    let emailError = document.getElementById('emailError');
    let passwordError = document.getElementById('passwordError');

    emailError.textContent = '';
    passwordError.textContent = '';

    let isValid = true;
    if (email === '') {
        emailError.textContent = 'Veuillez entrer votre adresse email.';
        isValid = false;
    }

    if (password === '') {
        passwordError.textContent = 'Veuillez entrer votre mot de passe.';
        isValid = false;
    }

    if (isValid) {
        let data = {
            email: email,
            password: password
        };

        const response = await login(data);
        if (!response.ok){
            const error = await response.json()
            const errorMessage = error.message;
            showToast('error', errorMessage);
            return;
        } else {
            showToast('success', 'vous etes connecté');
            window.location.replace('../pages/recettes/categories.php')

        }
    }
}


async function login(data) {
    // construction du fetch request 'POST'
    const url = '../php/auth/user_login.php';
    let response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    });

    return response
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
