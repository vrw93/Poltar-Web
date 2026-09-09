const passwordField = document.getElementById("pwField");
const passwordBtn = document.getElementById('pwBtn');

function showPassword(){
    if(passwordField.type == "password"){
        passwordField.type = "text";
        passwordBtn.innerHTML = '<i class="fa-solid fa-eye-slash"></i>';
    }else {
        passwordField.type = "password";
        passwordBtn.innerHTML = '<i class="fa-solid fa-eye"></i>';
    }
}

passwordBtn.addEventListener('click', showPassword);