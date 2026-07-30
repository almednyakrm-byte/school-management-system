**create_مواعيد.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $description = trim($_POST['description']);

    if (!empty($name) && !empty($date) && !empty($time) && !empty($description)) {
        // Insert data into database
        $query = "INSERT INTO مواعيد (name, date, time, description) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ssss", $name, $date, $time, $description);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_مواعيد.php');
        exit;
    }
}

// Include header
require_once '../includes/header.php';

// Include form
require_once '../includes/create_مواعيد_form.php';

// Include footer
require_once '../includes/footer.php';
?>


**create_مواعيد_form.php**

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h2 class="text-2xl font-bold text-slate-900 mb-4">Create New مواعيد</h2>
    <form id="create-muaeed-form" class="space-y-6" method="POST">
        <div class="grid grid-cols-1 gap-4">
            <div class="col-span-2">
                <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 bg-white border border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Name">
            </div>
            <div class="col-span-2">
                <label for="date" class="block text-sm font-medium text-slate-900">Date</label>
                <input type="date" id="date" name="date" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 bg-white border border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="col-span-2">
                <label for="time" class="block text-sm font-medium text-slate-900">Time</label>
                <input type="time" id="time" name="time" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 bg-white border border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="col-span-2">
                <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 bg-white border border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" rows="4"></textarea>
            </div>
        </div>
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create-muaeed-form').submit(function(event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/مواعيد.php',
                data: $(this).serialize(),
                success: function(response) {
                    window.location.href = 'list_مواعيد.php';
                }
            });
        });
    });
</script>


**footer.php**

</body>
</html>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New مواعيد</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>