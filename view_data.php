<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Database configuration
$servername = "localhost";
$username = "khush";
$password = "Khush@3160";
$database = "kapalin_homes_contact";

// Create database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle note update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_note'])) {
    $id = $conn->real_escape_string($_POST['id']);
    $note = $conn->real_escape_string($_POST['note']);

    $update_sql = "UPDATE contact_messages SET note = '$note' WHERE id = '$id'";
    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Note updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating note: {$conn->error}');</script>";
    }
}

// Fetch messages
$sql = "SELECT id, name, email, phone, subject, message, note FROM contact_messages";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kapalin Homes | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            text-align: left;
            padding: 10px;
            vertical-align: top;
        }

        th {
            background-color: #f4f4f4;
        }

        td.message {
            max-width: 150px;
            height: 120px;
            overflow: auto;
        }

        @media (max-width: 768px) {
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Contact Form Submissions</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Subject</th>
                    <th class="message">Message</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['subject']); ?></td>
                        <td class="message">
                            <div style="height: 120px; overflow-y: auto;">
                                <?php echo htmlspecialchars($row['message']); ?>
                            </div>
                        </td>
                        <td>
                            <form action="view_data.php" method="POST">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <textarea name="note" rows="3" class="form-control"
                                    placeholder="Add a note..."><?php echo htmlspecialchars($row['note']); ?></textarea>
                                <button type="submit" name="update_note" class="btn btn-primary btn-sm mt-2">Save</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
$conn->close();
?>