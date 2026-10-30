
<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");

$message = "";
$messageType = "error";

// Delete User
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_user"])) {
    $id = (int)($_POST["user_id"] ?? 0);

    if ($id > 0) {
        // Check complaint and maintenance history before deleting
        $check = $conn->prepare(
            "SELECT
                (SELECT COUNT(*) FROM complaints WHERE user_id = ?) +
                (SELECT COUNT(*) FROM maintenance WHERE user_id = ?) AS total"
        );

        $check->bind_param("ii", $id, $id);
        $check->execute();
        $total = (int)$check->get_result()->fetch_assoc()["total"];
        $check->close();

        if ($total > 0) {
            $message = "This user has complaint or maintenance records. "
                     . "The account was not deleted to protect the history.";
        } else {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute() && $stmt->affected_rows > 0) {
                $message = "User deleted successfully.";
                $messageType = "success";
            } else {
                $message = "User not found or could not be deleted.";
            }

            $stmt->close();
        }
    } else {
        $message = "Invalid user ID.";
    }
}

// Fetch all users
$result = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Users - Smart Society</title>

    <link rel="stylesheet" href="../css/style.css">

    <style>
        .users-box {
            width: 95%;
            max-width: 1100px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.10);
            overflow-x: auto;
        }

        .users-table {
            width: 100% !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
        }

        .users-table th,
        .users-table td {
            padding: 14px 12px !important;
            text-align: left !important;
            border-bottom: 1px solid #ddd !important;
            vertical-align: middle !important;
            overflow-wrap: anywhere;
        }

        .users-table th {
            background: #2563eb !important;
            color: white !important;
            font-weight: bold;
        }

        .users-table th:nth-child(1) { width: 7%; }
        .users-table th:nth-child(2) { width: 19%; }
        .users-table th:nth-child(3) { width: 25%; }
        .users-table th:nth-child(4) { width: 12%; }
        .users-table th:nth-child(5) { width: 20%; }
        .users-table th:nth-child(6) { width: 17%; }

        .users-table tr:hover {
            background: #f5f7fa !important;
        }

        .page-title,
        .page-description {
            text-align: center;
        }

        .page-description {
            margin-bottom: 20px;
        }

        .table-title {
            margin-bottom: 20px;
        }

        .delete-btn {
            background: #dc2626;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background: #b91c1c;
        }

        .message {
            width: 90%;
            max-width: 1050px;
            margin: 20px auto;
            padding: 12px;
            border-radius: 6px;
            background: #fee2e2;
            color: #991b1b;
            text-align: center;
        }

        .message.success {
            background: #dcfce7;
            color: #166534;
        }

        @media (max-width: 700px) {
            .users-box {
                overflow-x: auto;
            }

            .users-table {
                min-width: 850px !important;
            }
        }
    </style>
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
            <li><a href="maintenance.php">Maintenance</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<section class="section">

    <h1 class="page-title">👥 Registered Users</h1>

    <p class="page-description">
        View and manage registered residents of the society.
    </p>

    <?php if ($message !== "") { ?>
        <div class="message <?php echo $messageType === 'success' ? 'success' : ''; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php } ?>

    <div class="users-box">

        <h2 class="table-title">Society Residents</h2>

        <?php if ($result && mysqli_num_rows($result) > 0) { ?>

            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Flat No.</th>
                        <th>Mobile</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td>
                                <?php echo (int)$row["id"]; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["name"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["email"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["flat_no"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["mobile"]); ?>
                            </td>

                            <td>
                                <form method="POST" action="users.php"
                                      onsubmit="return confirm('Are you sure you want to delete this user?');">

                                    <input type="hidden" name="user_id"
                                           value="<?php echo (int)$row["id"]; ?>">

                                    <button type="submit" name="delete_user"
                                            class="delete-btn">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        <?php } else { ?>

            <p>No registered users found.</p>

        <?php } ?>

    </div>
</section>

</body>
</html>
