<?php
session_start();

include("../includes/db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM admin WHERE username = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {

        $admin = mysqli_fetch_assoc($result);

        if ($password === $admin["password"]) {

            $_SESSION["admin_id"] = $admin["id"];
            $_SESSION["admin_name"] = $admin["username"];

            header("Location: dashboard.php");
            exit();

        } else {
            $message = "Invalid username or password.";
        }

    } else {
        $message = "Invalid username or password.";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login - Smart Society</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<header>
    <nav class="navbar">

        <div class="logo">Smart Society</div>

        <ul class="nav-links">
            <li><a href="../index.php">Home</a></li>
        </ul>

    </nav>
</header>

<section class="section">

    <h1>🔐 Admin Login</h1>

    <p>Login to access the Admin Panel.</p>

    <?php if ($message != "") { ?>
        <p><strong><?php echo $message; ?></strong></p>
    <?php } ?>

    <div class="profile-card">

        <form method="POST">

            <label>Username</label>
            <input type="text" name="username" required>

            <br><br>

            <label>Password</label>
            <input type="password" name="password" required>

            <br><br>

            <button type="submit" class="btn">
                Admin Login
            </button>

        </form>

    </div>

</section>

</body>
</html>