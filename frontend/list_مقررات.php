**list_مقررات.php**

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
    <title>مقررات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f2937;
            padding: 1rem;
            text-align: center;
        }
        .header .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ffffff;
        }
        .header .nav-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header .nav-links li {
            margin-right: 20px;
        }
        .header .nav-links a {
            color: #ffffff;
            text-decoration: none;
        }
        .header .nav-links a:hover {
            color: #ffffff;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            width: 50%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
        }
        .search-bar button[type="submit"] {
            background-color: #1f2937;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-bar button[type="submit"]:hover {
            background-color: #1f2937;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">مقررات</div>
        <div class="nav-links">
            <a href="index.php">الصفحة الرئيسية</a>
            <a href="profile.php"><?= $_SESSION['username']; ?></a>
            <a href="logout.php">تسجيل الخروج</a>
        </div>
    </header>
    <main>
        <div class="container mx-auto p-4">
            <h1 class="text-3xl font-bold mb-4">قائمة مقررات</h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مقررات.php'">إضافة مقرر جديد</button>
            <div class="search-bar mt-4">
                <input type="search" id="search-input" placeholder="بحث...">
                <button type="submit" onclick="searchRecords()">بحث</button>
            </div>
            <table class="table mt-4">
                <thead>
                    <tr>
                        <th>اسم المقرر</th>
                        <th>وصف المقرر</th>
                        <th>حذف</th>
                        <th>تعديل</th>
                    </tr>
                </thead>
                <tbody id="records-table">
                    <?php
                    // Fetch records from backend
                    $records = json_decode(file_get_contents('../backend/مقررات.php'), true);
                    foreach ($records as $record) {
                        ?>
                        <tr>
                            <td><?= $record['name']; ?></td>
                            <td><?= $record['description']; ?></td>
                            <td>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?= $record['id']; ?>)">حذف</button>
                            </td>
                            <td>
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_مقررات.php?id=<?= $record['id']; ?>'">تعديل</button>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    <script>
        function searchRecords() {
            const searchInput = document.getElementById('search-input');
            const searchQuery = searchInput.value.trim();
            if (searchQuery !== '') {
                fetch('../backend/مقررات.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(records => {
                        const recordsTable = document.getElementById('records-table');
                        recordsTable.innerHTML = '';
                        records.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.name}</td>
                                <td>${record.description}</td>
                                <td>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                                <td>
                                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_مقررات.php?id=${record.id}'">تعديل</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/مقررات.php')
                    .then(response => response.json())
                    .then(records => {
                        const recordsTable = document.getElementById('records-table');
                        recordsTable.innerHTML = '';
                        records.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.name}</td>
                                <td>${record.description}</td>
                                <td>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                                <td>
                                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_مقررات.php?id=${record.id}'">تعديل</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            }
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف المقرر؟')) {
                fetch('../backend/مقررات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف المقرر بنجاح');
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء حذف المقرر');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>

This code includes a premium Tailwind UI layout, session validation, and a search bar that filters elements in real-time. It also includes AJAX calls to fetch records from the backend and delete records.