<?php
include("includes/db.php");
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $flat_no = $_POST["flat_no"];
    $mobile = $_POST["mobile"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $sql = "INSERT INTO users (name, flat_no, mobile, email, password)
            VALUES ('$name', '$flat_no', '$mobile', '$email', '$password')";
    if (mysqli_query($conn, $sql)) {
        $message = "Registration successful!";
    } else {
        $message = "Registration failed: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Smart Society</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">Smart Society</div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>
    <section class="section">
        <h2>Resident Registration</h2>
        <?php
        if ($message != "") {
            echo "<p>$message</p>";
        }
        ?>
        <form method="POST" class="form-box" onsubmit="return validateRegister()">
            <label>Name</label>
            <input type="text" name="name" id="name" required>
            <label>Flat Number</label>
            <input type="text" name="flat_no" id="flat_no" required>
            <label>Mobile Number</label>
            <input type="text" name="mobile" id="mobile" required>
            <label>Email</label>
            <input type="email" name="email" id="email" required>
            <label>Password</label>
            <input type="password" name="password" id="password" required>
            <button type="submit" class="btn">
                Register
            </button>
        </form>
        <p>
            Already have an account?
            <a href="login.php">Login</a>
        </p>
    </section>
    <script src="js/script.js"></script>
</body>
</html>