**create_طلاب.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة طالب جديد</h2>
        <form id="create-student-form">
            <div class="mb-4">
                <label for="name" class="text-slate-900 font-bold">اسم الطالب:</label>
                <input type="text" id="name" name="name" class="w-full p-2 mb-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="email" class="text-slate-900 font-bold">البريد الإلكتروني:</label>
                <input type="email" id="email" name="email" class="w-full p-2 mb-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="phone" class="text-slate-900 font-bold">رقم الهاتف:</label>
                <input type="tel" id="phone" name="phone" class="w-full p-2 mb-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="address" class="text-slate-900 font-bold">العنوان:</label>
                <textarea id="address" name="address" class="w-full p-2 mb-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#create-student-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/طلاب.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_طلاب.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**طلاب.php (backend)**

<?php
// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Check if all fields are filled
    if (!empty($name) && !empty($email) && !empty($phone) && !empty($address)) {
        // Insert data into database
        // Replace with your actual database connection and query
        $db = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');
        $stmt = $db->prepare('INSERT INTO students (name, email, phone, address) VALUES (:name, :email, :phone, :address)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->execute();

        // Redirect to list page
        echo 'success';
    } else {
        echo 'Error: All fields are required.';
    }
}
?>


Note: Replace the database connection and query in the backend file with your actual database credentials and query.