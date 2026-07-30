**list_مديرون.php**

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
    <title>مديرون</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
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
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
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
            background-color: #1a1d23;
            color: #fff;
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مديرون</span>
        <a href="profile.php">حسناً</a>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">قائمة المديرون</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مديرون.php'">إضافة جديد</button>
            <div class="search-bar">
                <input type="search" id="search-input" placeholder="بحث...">
                <button onclick="searchRecords()">بحث</button>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المدير</th>
                    <th>عنوان المدير</th>
                    <th>حالة المدير</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records-list">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const recordsList = document.getElementById('records-list');

        function searchRecords() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/مديرون.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    params: {
                        search: searchQuery
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const records = data.records;
                    recordsList.innerHTML = '';
                    records.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.اسم_المدير}</td>
                            <td>${record.عنوان_المدير}</td>
                            <td>${record.حالة_المدير}</td>
                            <td>
                                <a href="edit_مديرون.php?id=${record.id}" class="text-blue-500 hover:text-blue-700">تعديل</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsList.appendChild(row);
                    });
                })
                .catch(error => console.error(error));
            } else {
                fetch('../backend/مديرون.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const records = data.records;
                    recordsList.innerHTML = '';
                    records.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.اسم_المدير}</td>
                            <td>${record.عنوان_المدير}</td>
                            <td>${record.حالة_المدير}</td>
                            <td>
                                <a href="edit_مديرون.php?id=${record.id}" class="text-blue-500 hover:text-blue-700">تعديل</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsList.appendChild(row);
                    });
                })
                .catch(error => console.error(error));
            }
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا المدير؟')) {
                fetch('../backend/مديرون.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف المدير بنجاح');
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                })
                .catch(error => console.error(error));
            }
        }

        searchRecords();
    </script>
</body>
</html>

**backend/مديرون.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Search query
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $query = "SELECT * FROM المديرون WHERE اسم_المدير LIKE '%$searchQuery%' OR عنوان_المدير LIKE '%$searchQuery%' OR حالة_المدير LIKE '%$searchQuery%'";
} else {
    $query = "SELECT * FROM المديرون";
}

// Execute query
$result = $conn->query($query);

// Fetch records
$records = array();
while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}

// Output records
echo json_encode(array('records' => $records));

// Close connection
$conn->close();
?>

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM المديرون WHERE id = '$id'";
    $conn->query($query);
    echo json_encode(array('success' => true));
    exit;
}