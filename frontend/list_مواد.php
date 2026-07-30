**list_مواد.php**

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
    <title>مواد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d3748;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: center;
        }
        .table th {
            background-color: #2d3748;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar button {
            background-color: #2d3748;
            color: #fff;
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #3b4453;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مركز المعلومات</span>
        <a href="profile.php"><?= $_SESSION['username']; ?></a>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">قائمة المواد</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_مواد.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم المادة</th>
                    <th>اسم المادة</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?= $record['id']; ?></td>
                        <td><?= $record['name']; ?></td>
                        <td>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?= $record['id']; ?>)">حذف</button>
                        </td>
                        <td>
                            <a href="edit_مواد.php?id=<?= $record['id']; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchValue = searchInput.value.trim();
            if (searchValue) {
                fetch('../backend/مواد.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    params: {
                        search: searchValue
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const recordsElement = document.getElementById('records');
                    recordsElement.innerHTML = '';
                    data.forEach(record => {
                        const recordElement = document.createElement('tr');
                        recordElement.innerHTML = `
                            <td>${record.id}</td>
                            <td>${record.name}</td>
                            <td>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                            <td>
                                <a href="edit_مواد.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            </td>
                        `;
                        recordsElement.appendChild(recordElement);
                    });
                })
                .catch(error => console.error(error));
            } else {
                fetchRecords();
            }
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف المادة؟')) {
                fetch('../backend/مواد.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف المادة بنجاح');
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء حذف المادة');
                    }
                })
                .catch(error => console.error(error));
            }
        }

        function fetchRecords() {
            return fetch('../backend/مواد.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => data.records)
            .catch(error => console.error(error));
        }

        searchRecords();
    </script>
</body>
</html>

**backend/مواد.php**

<?php
// Fetch records from database
$records = array(
    array('id' => 1, 'name' => 'مادة 1'),
    array('id' => 2, 'name' => 'مادة 2'),
    array('id' => 3, 'name' => 'مادة 3')
);

if (isset($_GET['search'])) {
    $searchValue = $_GET['search'];
    $records = array_filter($records, function($record) use ($searchValue) {
        return strpos($record['name'], $searchValue) !== false;
    });
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Delete record from database
    // ...
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('records' => $records));
}