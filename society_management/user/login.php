<?php
session_start();
include("../includes/db.php");
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $sql = "SELECT * FROM users
            WHERE email = '$email'
            AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>User Login - Smart Society</title>
    <link rel="stylesheet"
          href="../css/style.css">
</head>
<body>
    <!-- Navbar -->
    <header>
        <nav class="navbar">
            <div class="logo">
                Smart Society
            </div>
            <ul class="nav-links">
                <li>
                    <a href="../index.php">Home</a>
                </li>
                <li>
                    <a href="../register.php">Register</a>
                </li>
            </ul>
        </nav>
    </header>
    <!-- Login Section -->
    <section class="section">
        <h2>User Login</h2>
        <?php
        if ($message != "") {
            echo "<p>$message</p>";
        }
        ?>
        <form method="POST"
              class="form-box">
            <label>Email</label>
            <input
                type="email"
                name="email"
                placeholder="Enter your email"
                required
            >
            <label>Password</label>
            <input
                type="password"
                name="password"
                placeholder="Enter your password"
                required
            >
            <button
                type="submit"
                class="btn">
                Login
            </button>
        </form>
        <p>
            New Resident?
            <a href="../register.php">
                Create Account
            </a>
        </p>
    </section>
</body>
</html>