**edit_مواعيد.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/مواعيد.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

// Set page title and mod slug
$pageTitle = 'Edit مواعيد';
$modSlug = 'مواعيد';

// Include header and navigation
include 'header.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-8">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $pageTitle ?></h1>

    <form id="edit-form" class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="title" class="block text-sm font-medium text-slate-900">Title</label>
                <input type="text" id="title" name="title" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['title'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"><?= $existingRecord['description'] ?></textarea>
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-slate-900">Date</label>
                <input type="date" id="date" name="date" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['date'] ?>">
            </div>
            <div>
                <label for="time" class="block text-sm font-medium text-slate-900">Time</label>
                <input type="time" id="time" name="time" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['time'] ?>">
            </div>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Update</button>
    </form>
</div>

<script>
    // Fetch existing record details via GET
    fetch('../backend/مواعيد.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            document.getElementById('title').value = data.title;
            document.getElementById('description').value = data.description;
            document.getElementById('date').value = data.date;
            document.getElementById('time').value = data.time;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();

        // Get form data
        const formData = new FormData(event.target);

        // Send AJAX PUT request
        fetch('../backend/مواعيد.php', {
            method: 'PUT',
            body: formData,
            headers: {
                'X-CSRF-Token': '<?= $_SESSION['csrf_token'] ?>'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_<?= $modSlug ?>.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/مواعيد.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit;
}

// Get ID
$id = $_GET['id'];

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    http_response_code(401);
    exit;
}

// Get existing record details
$existingRecord = get_record($id);

// Return JSON response
echo json_encode($existingRecord);

// Function to get record details
function get_record($id) {
    // TO DO: Implement database query to get record details
    // For demonstration purposes, return a dummy record
    return [
        'id' => $id,
        'title' => 'Dummy Title',
        'description' => 'Dummy Description',
        'date' => '2022-01-01',
        'time' => '12:00:00'
    ];
}
?>


**header.php**

<?php
// TO DO: Implement header template
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <!-- TO DO: Implement navigation template -->
</body>
</html>


**footer.php**

<?php
// TO DO: Implement footer template
?>
</body>
</html>


Note: This code assumes you have a `backend/مواعيد.php` file that handles the database query to get the existing record details. You should replace the dummy record data with the actual database query. Additionally, you should implement the header and footer templates in the `header.php` and `footer.php` files, respectively.