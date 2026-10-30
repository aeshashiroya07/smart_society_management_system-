<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");

$sql = "SELECT * FROM notices ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notices - Smart Society</title>
    <link rel="stylesheet" href="../css/style.css">

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
                <a href="complaint.php">Complaints</a>
            </li>
            <li>
                <a href="maintenance.php">Maintenance</a>
            </li>
            <li>
                <a href="profile.php">Profile</a>
            </li>
            <li>
                <a href="logout.php">Logout</a>
            </li>
        </ul>
    </nav>

</header>

<section class="section">
    <h1>📢 Society Notices</h1>
    <p>View the latest society notices.</p>

    <?php if (mysqli_num_rows($result) > 0) { ?>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="profile-card">
                <h2>
                    <?php echo htmlspecialchars($row["title"]); ?>
                </h2>
                <p>
                    <?php echo htmlspecialchars($row["description"]); ?>
                </p>
                <small>
                    Date:
                    <?php echo htmlspecialchars($row["date"]); ?>
                </small>
            </div>
            <br>
        <?php } ?>
    <?php } else { ?>
        <div class="profile-card">
            <p>No notices available.</p>
        </div>
    <?php } ?>
</section>
</body>
</html>