function validateRegister() {
    let mobile = document.getElementById("mobile").value;
    let password = document.getElementById("password").value;
    if (mobile.length != 10 || isNaN(mobile)) {
        alert("Please enter a valid 10-digit mobile number.");
        return false;
    }
    if (password.length < 6) {
        alert("Password must contain at least 6 characters.");
        return false;
    }
    return true;
}