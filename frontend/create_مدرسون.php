**create_مدرسون.php**

<?php
// Session validation
if (!isset($_SESSION['mod_slug'])) {
    header('Location: index.php');
    exit;
}

// Include header and navigation
include_once 'header.php';
include_once 'navigation.php';

// Include form script
include_once 'form_script.php';

?>

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold text-slate-900">إضافة مدرس</h1>
    <form id="create-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">الاسم</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="الاسم">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني</label>
            <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="البريد الإلكتروني">
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">رقم الهاتف</label>
            <input type="tel" id="phone" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="رقم الهاتف">
        </div>
        <div class="mb-4">
            <label for="address" class="block text-gray-700 text-sm font-bold mb-2">العنوان</label>
            <textarea id="address" name="address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="العنوان"></textarea>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">إضافة</button>
    </form>
</div>

<?php
// Include footer
include_once 'footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: '../backend/مدرسون.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data == 'success') {
                        window.location.href = 'list_<?php echo $_SESSION['mod_slug']; ?>.php';
                    } else {
                        alert('Error: ' + data);
                    }
                }
            });
        });
    });
</script>


**form_script.php**

<?php
// Include form validation script
include_once 'form_validation.php';
?>


**form_validation.php**

<?php
// Form validation script
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['address'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Validate form data
    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        echo 'Error: All fields are required.';
    } else {
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo 'Error: Invalid email address.';
        } else {
            // Validate phone number
            if (!preg_match('/^(\d{3}|\d{4})[\s.-]?\d{3}[\s.-]?\d{4}$/', $phone)) {
                echo 'Error: Invalid phone number.';
            } else {
                // Validate address
                if (strlen($address) < 10) {
                    echo 'Error: Address must be at least 10 characters long.';
                } else {
                    // Form data is valid
                    echo 'success';
                }
            }
        }
    }
}
?>