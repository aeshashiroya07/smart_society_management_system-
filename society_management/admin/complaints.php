<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $complaint_id = $_POST["complaint_id"];
    $status = $_POST["status"];

    $sql = "UPDATE complaints SET status = ? WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $status, $complaint_id);

    if (mysqli_stmt_execute($stmt)) {
        $message = "Complaint status updated successfully!";
    } else {
        $message = "Error updating complaint.";
    }

    mysqli_stmt_close($stmt);
}

$sql = "SELECT complaints.*, users.name
        FROM complaints
        JOIN users ON complaints.user_id = users.id
        ORDER BY complaints.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Complaints - Smart Society</title>

    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header>
    <nav class="navbar">

        <div class="logo">Smart Society - Admin</div>

        <ul class="nav-links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="complaints.php">Complaints</a></li>
            <li><a href="notices.php">Notices</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>

    </nav>
</header>
<section class="section">

    <h1>📝 Manage Complaints</h1>

    <p>View and manage resident complaints.</p>

    <?php if ($message != "") { ?>
        <p><strong><?php echo $message; ?></strong></p>
    <?php } ?>

    <div class="profile-card">

        <h2>All Complaints</h2>

        <?php if (mysqli_num_rows($result) > 0) { ?>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                <div class="complaint-item">
                    <p>
                        <strong>Resident:</strong>
                        <?php echo htmlspecialchars($row["name"]); ?>
                    </p>
                    <p>
                        <strong>Complaint:</strong>
                        <?php echo htmlspecialchars($row["complaint"]); ?>
                    </p>
                    <p>
                        <strong>Date:</strong>
                        <?php echo htmlspecialchars($row["date"]); ?>
                    </p>
                    <p>
                        <strong>Current Status:</strong>
                        <?php echo htmlspecialchars($row["status"]); ?>
                    </p>
                    <form method="POST">

                        <input
                            type="hidden"
                            name="complaint_id"
                            value="<?php echo $row["id"]; ?>"
                        >
                        <label><strong>Update Status:</strong></label>
                        <select name="status">
                            <option value="Pending"
                                <?php if ($row["status"] == "Pending") echo "selected"; ?>>
                                Pending
                            </option>
                            <option value="In Progress"
                                <?php if ($row["status"] == "In Progress") echo "selected"; ?>>
                                In Progress
                            </option>
                            <option value="Resolved"
                                <?php if ($row["status"] == "Resolved") echo "selected"; ?>>
                                Resolved
                            </option>
                        </select>
                        <br><br>
                        <button type="submit" class="btn">
                            Update Status
                        </button>
                    </form>
                </div>
                <hr>
            <?php } ?>
        <?php } else { ?>
            <p>No complaints found.</p>
        <?php } ?>
    </div>
</section>
</body>
</html>