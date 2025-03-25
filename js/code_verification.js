async function validateCode() {
    const code = document.getElementById('code').value;

    const response = await verify(code);
    if (response.ok) {
        showToast('success', 'Compte vérifier');
        window.location.replace('../login.php')
    } else {
        showToast('error', 'code de verification invalid');
    }
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


 async function verify(code) {
    let url = "../../php/auth/verify_code.php";
    let email = new URLSearchParams(window.location.search).get('email');
    const data = {
        email: email,
        code: code
    }
    const response = fetch (url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    });

    return response; 
 }