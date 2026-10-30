<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
$user_name = $_SESSION["user_name"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>My Profile - Smart Society</title>
    <link rel="stylesheet"
          href="../css/style.css">
</head>
<body>
<header>
    <nav class="navbar">
        <div class="logo">
            Smart Society
        </div>
        <ul class="nav-links">
            <li>
                <a href="dashboard.php">Dashboard</a>
            </li>
            <li>
                <a href="notices.php">Notices</a>
            </li>
            <li>
                <a href="logout.php">Logout</a>
            </li>
        </ul>
    </nav>
</header>
<section class="section">
    <h1>👤 My Profile</h1>
    <div class="profile-card">
        <h2>Resident Information</h2>
        <p>
            <strong>Name:</strong>
            <?php echo htmlspecialchars($user_name); ?>
        </p>
        <p>
            <strong>Account Type:</strong>
            Resident
        </p>
        <p>
            <strong>Status:</strong>
            Active
        </p>
        <button class="btn">
            Edit Profile
        </button>
    </div>
</section>
</body>
</html>