**edit_درجات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get record ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$record = json_decode(file_get_contents('../backend/درجات.php?id=' . $id), true);

// Check if record exists
if (empty($record)) {
    echo 'Record not found.';
    exit;
}

// Set page title and mod slug
$page_title = 'Edit درجات';
$mod_slug = 'درجات';

// Include header and navigation
include 'header.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $page_title ?></h1>

    <form id="edit-record-form" class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="<?= $record['name'] ?>">
            </div>
            <div>
                <label for="grade" class="block text-sm font-medium text-slate-900">Grade:</label>
                <input type="number" id="grade" name="grade" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="<?= $record['grade'] ?>">
            </div>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Update درجات</button>
    </form>
</div>

<script>
    // Fetch existing record details via GET
    fetch('../backend/درجات.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            document.getElementById('name').value = data.name;
            document.getElementById('grade').value = data.grade;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX PUT request
    document.getElementById('edit-record-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('../backend/درجات.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_' + <?= $mod_slug ?> + '.php';
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


**backend/درجات.php**

<?php
// Check if record ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'Record ID not set.'));
    exit;
}

// Fetch existing record details from database
$record = array(
    'id' => $_GET['id'],
    'name' => 'John Doe',
    'grade' => 90
); // Replace with actual database query

// Update record details via PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents('php://input'), $data);
    $record['name'] = $data['name'];
    $record['grade'] = $data['grade'];
    echo json_encode(array('success' => true));
} else {
    echo json_encode($record);
}
?>