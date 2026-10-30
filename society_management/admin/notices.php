<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");

$message = "";

if (isset($_POST["add_notice"])) {

    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $date = date("Y-m-d");

    $sql = "INSERT INTO notices (title, description, date)
            VALUES (?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $title, $description, $date);

    if (mysqli_stmt_execute($stmt)) {
        $message = "Notice added successfully!";
    } else {
        $message = "Error adding notice.";
    }

    mysqli_stmt_close($stmt);
}

if (isset($_POST["update_notice"])) {

    $id = $_POST["id"];
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $date = date("Y-m-d");

    $sql = "UPDATE notices
            SET title = ?, description = ?, date = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $title, $description, $date, $id);

    if (mysqli_stmt_execute($stmt)) {
        $message = "Notice updated successfully!";
    } else {
        $message = "Error updating notice.";
    }

    mysqli_stmt_close($stmt);
}

if (isset($_GET["delete"])) {

    $id = $_GET["delete"];

    $sql = "DELETE FROM notices WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        $message = "Notice deleted successfully!";
    } else {
        $message = "Error deleting notice.";
    }

    mysqli_stmt_close($stmt);
}

$edit_notice = null;

if (isset($_GET["edit"])) {

    $id = $_GET["edit"];

    $sql = "SELECT * FROM notices WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $edit_result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($edit_result) == 1) {
        $edit_notice = mysqli_fetch_assoc($edit_result);
    }

    mysqli_stmt_close($stmt);
}

$sql = "SELECT * FROM notices ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Notices - Smart Society</title>

    <link rel="stylesheet" href="../css/style.css">

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
                <a href="logout.php">Logout</a>
            </li>
        </ul>
    </nav>
</header>
<section class="section">
    <h1>Manage Notices</h1>
    <p>Add, update and delete society notices.</p>
    <?php if ($message != "") { ?>
        <p>
            <strong>
                <?php echo htmlspecialchars($message); ?>
            </strong>
        </p>
    <?php } ?>
    <div class="profile-card">

        <?php if ($edit_notice != null) { ?>
            <h2>Update Notice</h2>
            <form method="POST">
                <input type="hidden"
                       name="id"
                       value="<?php echo $edit_notice["id"]; ?>">
                <label>Notice Title</label>
                <input type="text"
                       name="title"
                       value="<?php echo htmlspecialchars($edit_notice["title"]); ?>"
                       required>
                <br><br>
                <label>Description</label>
                <textarea name="description"
                          rows="5"
                          required><?php echo htmlspecialchars($edit_notice["description"]); ?></textarea>
                <br><br>
                <button type="submit"
                        name="update_notice"
                        class="btn">
                    Update Notice
                </button>
                <a href="notices.php" class="btn">
                    Cancel
                </a>
            </form>
        <?php } else { ?>
            <h2>Add New Notice</h2>
            <form method="POST">
                <label>Notice Title</label>
                <input type="text"
                       name="title"
                       placeholder="Enter notice title"
                       required>
                <br><br>
                <label>Description</label>
                <textarea name="description"
                          rows="5"
                          placeholder="Enter notice description"
                          required></textarea>
                <br><br>
                <button type="submit"
                        name="add_notice"
                        class="btn">
                    Add Notice
                </button>
            </form>
        <?php } ?>
    </div>
    <br>
    <div class="profile-card">

        <h2>All Notices</h2>
        <?php if (mysqli_num_rows($result) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="notice-card">
                    <h3>
                        <?php
                        echo htmlspecialchars($row["title"]);
                        ?>
                    </h3>
                    <p>
                        <?php
                        echo htmlspecialchars($row["description"]);
                        ?>
                    </p>
                    <small>
                        Posted:
                        <?php
                        echo htmlspecialchars($row["date"]);
                        ?>
                    </small>
                    <br><br>
                    <a href="notices.php?edit=<?php echo $row["id"]; ?>"
                       class="btn">
                        Update
                    </a>
                    <a href="notices.php?delete=<?php echo $row["id"]; ?>"
                       class="btn"
                       onclick="return confirm('Are you sure you want to delete this notice?');">
                        Delete
                    </a>
                </div>
                <hr>
            <?php } ?>
        <?php } else { ?>
            <p>No notices available.</p>
        <?php } ?>
    </div>
</section>
</body>
</html>