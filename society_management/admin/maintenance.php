<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");
$message = "";
/* ADD MAINTENANCE */
if (isset($_POST["add_maintenance"])) {

    $user_id = $_POST["user_id"];
    $month = trim($_POST["month"]);
    $amount = $_POST["amount"];
    $status = $_POST["status"];

    $sql = "INSERT INTO maintenance (user_id, month, amount, status)
            VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "isds",
        $user_id,
        $month,
        $amount,
        $status
    );
    if (mysqli_stmt_execute($stmt)) {
        $message = "Maintenance added successfully!";
    } else {
        $message = "Error adding maintenance.";
    }
    mysqli_stmt_close($stmt);
}
/* UPDATE MAINTENANCE */
if (isset($_POST["update_maintenance"])) {

    $id = $_POST["id"];
    $user_id = $_POST["user_id"];
    $month = trim($_POST["month"]);
    $amount = $_POST["amount"];
    $status = $_POST["status"];

    $sql = "UPDATE maintenance
            SET user_id = ?, month = ?, amount = ?, status = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "isdsi",
        $user_id,
        $month,
        $amount,
        $status,
        $id
    );
    if (mysqli_stmt_execute($stmt)) {
        $message = "Maintenance updated successfully!";
    } else {
        $message = "Error updating maintenance.";
    }
    mysqli_stmt_close($stmt);
}
/* DELETE MAINTENANCE */
if (isset($_GET["delete"])) {

    $id = $_GET["delete"];

    $sql = "DELETE FROM maintenance WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        $message = "Maintenance deleted successfully!";
    } else {
        $message = "Error deleting maintenance.";
    }

    mysqli_stmt_close($stmt);
}
/* EDIT MAINTENANCE */
$edit_maintenance = null;
if (isset($_GET["edit"])) {

    $id = $_GET["edit"];

    $sql = "SELECT * FROM maintenance WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    $edit_result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($edit_result) == 1) {
        $edit_maintenance = mysqli_fetch_assoc($edit_result);
    }

    mysqli_stmt_close($stmt);
}
/* GET ALL USERS */
$user_sql = "SELECT id, name, flat_no FROM users ORDER BY name";

$user_result = mysqli_query($conn, $user_sql);
/* GET ALL MAINTENANCE RECORDS */
$sql = "SELECT maintenance.*, users.name, users.flat_no
        FROM maintenance
        INNER JOIN users
        ON maintenance.user_id = users.id
        ORDER BY maintenance.id DESC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Maintenance - Smart Society</title>

    <link rel="stylesheet"
          href="../css/style.css">

</head>
<body>
<header>
    <nav class="navbar">
        <div class="logo">
            Smart Society - Admin
        </div>
        <ul class="nav-links">
            <li>
                <a href="dashboard.php">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="users.php">
                    Users
                </a>
            </li>
            <li>
                <a href="complaints.php">
                    Complaints
                </a>
            </li>
            <li>
                <a href="notices.php">
                    Notices
                </a>
            </li>
            <li>
                <a href="maintenance.php">
                    Maintenance
                </a>
            </li>
            <li>
                <a href="logout.php">
                    Logout
                </a>
            </li>
        </ul>
    </nav>
</header>
<section class="section">
    <h1>💰 Manage Maintenance</h1>
    <p>
        Add, update and delete resident maintenance records.
    </p>
    <?php if ($message != "") { ?>
        <p>
            <strong>
                <?php echo htmlspecialchars($message); ?>
            </strong>
        </p>
    <?php } ?>
    <div class="profile-card">
        <?php if ($edit_maintenance != null) { ?>
            <h2>Update Maintenance</h2>
            <form method="POST">
                <input type="hidden"
                       name="id"
                       value="<?php echo $edit_maintenance["id"]; ?>">
                <label>Select Resident</label>
                <select name="user_id" required>
                    <option value="">
                        Select Resident
                    </option>
                    <?php
                    mysqli_data_seek($user_result, 0);
                    while ($user = mysqli_fetch_assoc($user_result)) {
                    ?>
                        <option
                            value="<?php echo $user["id"]; ?>"
                            <?php
                            if ($user["id"] == $edit_maintenance["user_id"]) {
                                echo "selected";
                            }
                            ?>
                        >
                            <?php
                            echo htmlspecialchars($user["name"]);
                            ?>
                            -
                            <?php
                            echo htmlspecialchars($user["flat_no"]);
                            ?>
                        </option>
                    <?php } ?>
                </select>
                <br>
                <br>
                <label>Month</label>
                <input type="text"
                       name="month"
                       value="<?php echo htmlspecialchars($edit_maintenance["month"]); ?>"
                       placeholder="Example: August 2026"
                       required>
                <br>
                <br>
                <label>Amount</label>
                <input type="number"
                       name="amount"
                       value="<?php echo htmlspecialchars($edit_maintenance["amount"]); ?>"
                       required>
                <br>
                <br>
                <label>Status</label>
                <select name="status" required>
                    <option value="Paid"
                        <?php
                        if ($edit_maintenance["status"] == "Paid") {
                            echo "selected";
                        }
                        ?>
                    >
                        Paid
                    </option>
                    <option value="Unpaid"
                        <?php
                        if ($edit_maintenance["status"] == "Unpaid") {
                            echo "selected";
                        }
                        ?>
                    >
                        Unpaid
                    </option>
                </select>
                <br>
                <br>
                <button type="submit"
                        name="update_maintenance"
                        class="btn">
                    Update Maintenance
                </button>
                <a href="maintenance.php"
                   class="btn">
                    Cancel
                </a>
            </form>
        <?php } else { ?>
            <h2>Add Maintenance</h2>
            <form method="POST">
                <label>Select Resident</label>
                <select name="user_id" required>
                    <option value="">
                        Select Resident
                    </option>
                    <?php while ($user = mysqli_fetch_assoc($user_result)) { ?>
                        <option value="<?php echo $user["id"]; ?>">
                            <?php
                            echo htmlspecialchars($user["name"]);
                            ?>
                            -
                            <?php
                            echo htmlspecialchars($user["flat_no"]);
                            ?>
                        </option>
                    <?php } ?>
                </select>
                <br>
                <br>
                <label>Month</label>
                <input type="text"
                       name="month"
                       placeholder="Example: August 2026"
                       required>
                <br>
                <br>
                <label>Amount</label>
                <input type="number"
                       name="amount"
                       placeholder="Enter amount"
                       required>
                <br>
                <br>
                <label>Status</label>
                <select name="status" required>
                    <option value="Unpaid">
                        Unpaid
                    </option>
                    <option value="Paid">
                        Paid
                    </option>
                </select>
                <br>
                <br>
                <button type="submit"
                        name="add_maintenance"
                        class="btn">
                    Add Maintenance
                </button>
            </form>
        <?php } ?>
    </div>
    <br>
    <div class="profile-card">
        <h2>All Maintenance Records</h2>
        <?php if (mysqli_num_rows($result) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="maintenance-item">
                    <p>
                        <strong>Resident:</strong>
                        <?php
                        echo htmlspecialchars($row["name"]);
                        ?>
                    </p>
                    <p>
                        <strong>Flat No:</strong>
                        <?php
                        echo htmlspecialchars($row["flat_no"]);
                        ?>
                    </p>
                    <p>
                        <strong>Month:</strong>
                        <?php
                        echo htmlspecialchars($row["month"]);
                        ?>
                    </p>
                    <p>
                        <strong>Amount:</strong>
                        ₹<?php
                        echo htmlspecialchars($row["amount"]);
                        ?>
                    </p>
                    <p>
                        <strong>Status:</strong>
                        <?php
                        echo htmlspecialchars($row["status"]);
                        ?>
                    </p>
                    <br>
                    <a href="maintenance.php?edit=<?php echo $row["id"]; ?>"
                       class="btn">
                        Update
                    </a>
                    <a href="maintenance.php?delete=<?php echo $row["id"]; ?>"
                       class="btn"
                       onclick="return confirm('Are you sure you want to delete this maintenance record?');">
                        Delete
                    </a>
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