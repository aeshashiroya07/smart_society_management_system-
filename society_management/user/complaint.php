<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
include("../includes/db.php");
$user_id = $_SESSION["user_id"];
$message = "";
/* Submit Complaint */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complaint = $_POST["complaint"];
    $date = date("Y-m-d");
    $sql = "INSERT INTO complaints (user_id, complaint, status, date)
            VALUES (?, ?, 'Pending', ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "iss",
        $user_id,
        $complaint,
        $date
    );
    if (mysqli_stmt_execute($stmt)) {
        $message = "Complaint submitted successfully!";
    } else {
        $message = "Error submitting complaint.";
    }
    mysqli_stmt_close($stmt);
}
/* Get User Complaints */
$sql = "SELECT * FROM complaints
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
    <title>Complaints - Smart Society</title>
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
    <h1>📝 Complaints</h1>
    <p>
        Submit your complaint and check its status.
    </p>
    <?php if ($message != "") { ?>
        <p>
            <strong>
                <?php echo $message; ?>
            </strong>
        </p>
    <?php } ?>
    <!-- Complaint Form -->
    <div class="profile-card">
        <h2>Submit Complaint</h2>
        <form method="POST">
            <textarea
                name="complaint"
                rows="6"
                placeholder="Enter your complaint"
                required
            ></textarea>
            <br><br>
            <button type="submit" class="btn">
                Submit Complaint
            </button>
        </form>
    </div>
    <!-- Previous Complaints -->
    <div class="profile-card">
        <h2>My Complaints</h2>
        <?php if (mysqli_num_rows($result) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="complaint-item">
                    <p>
                        <strong>Complaint:</strong>
                        <?php echo htmlspecialchars($row["complaint"]); ?>
                    </p>
                    <p>
                        <strong>Status:</strong>
                        <?php echo htmlspecialchars($row["status"]); ?>
                    </p>
                    <p>
                        <strong>Date:</strong>
                        <?php echo htmlspecialchars($row["date"]); ?>
                    </p>
                </div>
                <hr>
            <?php } ?>
        <?php } else { ?>
            <p>No complaints submitted yet.</p>
        <?php } ?>
    </div>
</section>
</body>
</html>
<?php
mysqli_stmt_close($stmt);
?>