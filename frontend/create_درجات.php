**create_درجات.php**

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
    <h1 class="text-3xl font-bold text-slate-900 mb-4">إضافة درجات</h1>

    <form id="create-form" class="bg-white rounded-lg shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900 mb-2">اسم الطالب</label>
                <input type="text" id="name" name="name" class="block w-full p-2 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="grade" class="block text-sm font-medium text-slate-900 mb-2">درجة الطالب</label>
                <input type="number" id="grade" name="grade" class="block w-full p-2 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="subject" class="block text-sm font-medium text-slate-900 mb-2">المادة</label>
                <input type="text" id="subject" name="subject" class="block w-full p-2 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="semester" class="block text-sm font-medium text-slate-900 mb-2">الفصل</label>
                <input type="text" id="semester" name="semester" class="block w-full p-2 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/درجات.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'list_درجات.php';
                    } else {
                        alert(response.message);
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


**درجات.php (backend)**

<?php
// Database connection
include 'db.php';

// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['grade']) && isset($_POST['subject']) && isset($_POST['semester'])) {
    // Prepare SQL query
    $sql = "INSERT INTO درجات (name, grade, subject, semester) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $_POST['name'], $_POST['grade'], $_POST['subject'], $_POST['semester']);
    $stmt->execute();

    // Check if query is successful
    if ($stmt->affected_rows > 0) {
        $response = array('success' => true, 'message' => 'درجة جديدة تمت إضافتها بنجاح');
    } else {
        $response = array('success' => false, 'message' => 'حدث خطأ أثناء إضافة الدرجة');
    }
    echo json_encode($response);
} else {
    echo json_encode(array('success' => false, 'message' => 'بيانات الإضافة غير صالحة'));
}
?>