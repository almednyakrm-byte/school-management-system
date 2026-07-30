**create_مقررات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-slate-900 text-lg font-bold mb-4">إضافة مقرر</h2>
        <form id="create-mqrats-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="text-slate-900 text-sm font-bold">اسم المقرر</label>
                    <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="description" class="text-slate-900 text-sm font-bold">وصف المقرر</label>
                    <textarea id="description" name="description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                </div>
            </div>
            <div class="flex justify-between">
                <button type="button" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg" id="reset-form">إعادة تعيين</button>
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg" id="submit-form">حفظ</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#create-mqrats-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/مقررات.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_مقررات.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });

        $('#reset-form').click(function() {
            $('#create-mqrats-form')[0].reset();
        });
    });
</script>

<?php
// Include footer
require_once 'footer.php';
?>


**مقررات.php (backend)**

<?php
// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['description'])) {
    // Connect to database
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert data into database
    $name = $_POST['name'];
    $description = $_POST['description'];
    $sql = "INSERT INTO مقررات (name, description) VALUES ('$name', '$description')";
    if ($conn->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo 'Error: ' . $sql . '<br>' . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>


Note: This code assumes that you have a database table named `مقررات` with columns `name` and `description`. You should replace the database credentials and table name with your actual values. Additionally, this code does not include any validation or sanitization of user input, which is a security risk. You should add proper validation and sanitization to prevent SQL injection and other security vulnerabilities.