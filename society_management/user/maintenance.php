<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
include("../includes/db.php");
$user_id = $_SESSION["user_id"];
$sql = "SELECT * FROM maintenance
        WHERE user_id = ?
        ORDER BY id DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Maintenance - Smart Society</title>
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
    <h1>💰 Maintenance</h1>
    <p>
        View your society maintenance information.
    </p>
    <div class="profile-card">
        <h2>My Maintenance Records</h2>
        <?php if (mysqli_num_rows($result) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="maintenance-item">
                    <p>
                        <strong>Month:</strong>
                        <?php echo htmlspecialchars($row["month"]); ?>
                    </p>
                    <p>
                        <strong>Amount:</strong>
                        ₹<?php echo htmlspecialchars($row["amount"]); ?>
                    </p>
                    <p>
                        <strong>Status:</strong>
                        <?php echo htmlspecialchars($row["status"]); ?>
                    </p>
                </div>
                <hr>
            <?php } ?>
        <?php } else { ?>
            <p>
                No maintenance records available.
            </p>
        <?php } ?>
    </div>
</section>
</body>
</html>
<?php
mysqli_stmt_close($stmt);
?>