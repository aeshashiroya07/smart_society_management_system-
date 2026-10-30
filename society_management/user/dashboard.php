<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
$user_name = $_SESSION["user_name"];
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Smart Society</title>
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
                    <a href="dashboard.php">Dashboard</a>
                </li>
                <li>
                    <a href="logout.php">Logout</a>
                </li>
            </ul>
        </nav>
    </header>
    <!-- Dashboard -->
    <section class="section">
        <h1>
            Welcome, <?php echo htmlspecialchars($user_name); ?>! 👋
        </h1>
        <p>
            Welcome to your Smart Society Dashboard.
        </p>
        <!-- Dashboard Cards -->
        <div class="dashboard-cards">
            <div class="dashboard-card">
                <h3>📢 Notices</h3>
                <p>
                    View important society notices.
                </p>
                <a href="notices.php" class="btn">
                    View Notices
                </a>
            </div>
            <div class="dashboard-card">
                <h3>📝 Complaints</h3>
                <p>
                    Submit and check your complaints.
                </p>
                <a href="complaint.php" class="btn">
                    Complaints
                </a>
            </div>
            <div class="dashboard-card">
                <h3>💰 Maintenance</h3>
                <p>
                    View your maintenance information.
                </p>
                <a href="maintenance.php" class="btn">
                    Maintenance
                </a>
            </div>
            <div class="dashboard-card">
                <h3>👤 My Profile</h3>
                <p>
                    View your profile information.
                </p>
                <a href="profile.php" class="btn">
                    My Profile
                </a>
            </div>
        </div>
    </section>
</body>
</html>