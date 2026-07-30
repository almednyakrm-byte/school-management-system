**edit_مواد.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = json_decode(file_get_contents('../backend/مواد.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit مواد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-slate-900 {
            background-color: #1A1D23;
        }
        .text-indigo-500 {
            color: #6B5CF2;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4 bg-slate-900 rounded-md">
        <h1 class="text-3xl text-indigo-500 mb-4">Edit مواد</h1>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-300">Name:</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-md" value="<?= $existingRecord['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-300">Description:</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-md"><?= $existingRecord['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مواد.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_مواد.php';
                        } else {
                            alert('Error updating record');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/مواد.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo 'Invalid request';
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Connect to database
$conn = new PDO('dsn', 'username', 'password');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch existing record details
$stmt = $conn->prepare('SELECT * FROM materials WHERE id = :id');
$stmt->bindParam(':id', $id);
$stmt->execute();
$existingRecord = $stmt->fetch();

// Return JSON response
echo json_encode($existingRecord);

// Close database connection
$conn = null;
?>


**backend/edit_material.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo 'Invalid request';
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Connect to database
$conn = new PDO('dsn', 'username', 'password');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Update record
$stmt = $conn->prepare('UPDATE materials SET name = :name, description = :description WHERE id = :id');
$stmt->bindParam(':id', $id);
$stmt->bindParam(':name', $_POST['name']);
$stmt->bindParam(':description', $_POST['description']);
$stmt->execute();

// Return success response
echo 'success';

// Close database connection
$conn = null;
?>