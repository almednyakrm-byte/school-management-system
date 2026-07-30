**create_معلمين.php**

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
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة معلم</h2>
        <form id="create_maalem" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="text-slate-900 font-bold">الاسم</label>
                    <input type="text" id="name" name="name" class="block w-full p-2 text-slate-900 border border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="email" class="text-slate-900 font-bold">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" class="block w-full p-2 text-slate-900 border border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
            </div>
            <div>
                <label for="phone" class="text-slate-900 font-bold">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 text-slate-900 border border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div>
                <label for="address" class="text-slate-900 font-bold">العنوان</label>
                <textarea id="address" name="address" class="block w-full p-2 text-slate-900 border border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create_maalem').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/معلمين.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'list_معلمين.php';
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


**Note:** This code assumes you have jQuery and Bootstrap installed in your project. You may need to adjust the CSS classes and JavaScript code to match your specific Tailwind UI setup. Additionally, you should replace `../backend/معلمين.php` with the actual URL of your backend script that handles the form submission.