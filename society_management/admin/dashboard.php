<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION["admin_name"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Smart Society</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            max-width: 900px;
            margin: 30px auto;
        }
        .admin-card {
            background: white;
            padding: 30px;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.10);
        }
        .admin-card h2 {
            margin-bottom: 10px;
        }
        .admin-card p {
            margin-bottom: 20px;
        }
        .admin-card .btn {
            text-decoration: none;
        }
        @media (max-width: 600px) {
            .admin-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<header>
    <nav class="navbar">
        <div class="logo">
            Smart Society - Admin
        </div>
        <ul class="nav-links">
            <li>
                <a href="dashboard.php">Dashboard</a>
            </li>
            <li>
                <a href="users.php">Users</a>
            </li>
            <li>
                <a href="complaints.php">Complaints</a>
            </li>
            <li>
                <a href="notices.php">Notices</a>
            </li>
            <li>
                <a href="maintenance.php">Maintenance</a>
            </li>
            <li>
                <a href="logout.php">Logout</a>
            </li>
        </ul>
    </nav>
</header>
<section class="section">
    <h1>Welcome, <?php echo htmlspecialchars($admin_name); ?>!</h1>
    <p>
        Welcome to the Smart Society Admin Panel.
    </p>
    <div class="admin-cards">
        <div class="admin-card">
            <h2>👥 Users</h2>
            <p>
                View registered society residents.
            </p>
            <a href="users.php" class="btn">
                Manage Users
            </a>
        </div>
        <div class="admin-card">
            <h2>📝 Complaints</h2>
            <p>
                View and manage resident complaints.
            </p>
            <a href="complaints.php" class="btn">
                Manage Complaints
            </a>
        </div>
        <div class="admin-card">
            <h2>📢 Notices</h2>
            <p>
                Add, update and delete society notices.
            </p>
            <a href="notices.php" class="btn">
                Manage Notices
            </a>
        </div>
        <div class="admin-card">
            <h2>💰 Maintenance</h2>
            <p>
                Manage resident maintenance records.
            </p>
            <a href="maintenance.php" class="btn">
                Manage Maintenance
            </a>
        </div>
    </div>
</section>
</body>
</html>