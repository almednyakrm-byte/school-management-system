**list_مدرسون.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدرسون</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1d23;
        }
        .text-indigo-500 {
            color: #6b6ecf;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="container mx-auto p-4 mt-4 bg-white rounded-lg shadow-md">
        <header class="flex justify-between items-center mb-4">
            <a href="index.php" class="text-indigo-500 hover:text-indigo-700">الرئيسية</a>
            <div class="flex items-center">
                <span class="text-indigo-500 mr-2">مرحباً, <?php echo $_SESSION['username']; ?></span>
                <a href="logout.php" class="text-red-500 hover:text-red-700">تسجيل الخروج</a>
            </div>
        </header>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl text-indigo-500">مدرسون</h2>
            <a href="create_مدرسون.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
        </div>
        <div class="flex justify-between items-center mb-4">
            <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="بحث...">
            <button id="search-btn" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">بحث</button>
        </div>
        <table class="w-full table-auto">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">اسم</th>
                    <th class="px-4 py-2">تليفون</th>
                    <th class="px-4 py-2">إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $url = '../backend/مدرسون.php';
                $response = file_get_contents($url);
                $data = json_decode($response, true);
                foreach ($data as $record) {
                    ?>
                    <tr>
                        <td class="px-4 py-2"><?php echo $record['اسم']; ?></td>
                        <td class="px-4 py-2"><?php echo $record['تليفون']; ?></td>
                        <td class="px-4 py-2">
                            <a href="edit_مدرسون.php?id=<?php echo $record['id']; ?>" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Search functionality
        const searchInput = document.getElementById('search');
        const searchBtn = document.getElementById('search-btn');
        searchBtn.addEventListener('click', () => {
            const searchQuery = searchInput.value;
            fetch('../backend/مدرسون.php?search=' + searchQuery)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-4 py-2">${record['اسم']}</td>
                            <td class="px-4 py-2">${record['تليفون']}</td>
                            <td class="px-4 py-2">
                                <a href="edit_مدرسون.php?id=${record['id']}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record['id']})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        });

        // Delete record functionality
        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/مدرسون.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

This code includes the following features:

* Session validation to ensure the user is logged in before accessing the page.
* A premium Tailwind UI design with a specific color palette.
* A header navigation bar with links to the index page, current user info, and logout.
* A table showing a list of records with actions to edit and delete each record.
* A search bar that filters elements in real-time.
* AJAX JavaScript code using Fetch API to fetch records from the backend and delete records.

Note that this code assumes that the backend API is implemented to handle GET and DELETE requests for the `مدرسون` module.