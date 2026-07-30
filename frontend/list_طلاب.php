**list_طلاب.php**

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
    <title>طلاب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1d23;
        }
        .text-indigo-500 {
            color: #6b7280;
        }
    </style>
</head>
<body class="bg-slate-900 text-indigo-500">
    <div class="container mx-auto p-4">
        <header class="bg-slate-900 py-4">
            <nav class="flex justify-between items-center">
                <a href="index.php" class="text-indigo-500 hover:text-white">الرئيسية</a>
                <div class="flex items-center">
                    <span class="text-indigo-500"><?= $_SESSION['username'] ?></span>
                    <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='logout.php'">تسجيل الخروج</button>
                </div>
            </nav>
        </header>
        <main class="bg-slate-900 p-4">
            <h1 class="text-3xl text-indigo-500">طلاب</h1>
            <div class="flex justify-between items-center mb-4">
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='create_طلاب.php'">إضافة جديد</button>
                <input type="search" id="search" class="w-full p-2 text-indigo-500 rounded" placeholder="بحث...">
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th>اسم الطالب</th>
                        <th>تاريخ الميلاد</th>
                        <th>حذف</th>
                        <th>تعديل</th>
                    </tr>
                </thead>
                <tbody id="records">
                    <!-- Records will be loaded here -->
                </tbody>
            </table>
        </main>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const recordsTable = document.getElementById('records');

        searchInput.addEventListener('input', () => {
            const searchQuery = searchInput.value.toLowerCase();
            const records = Array.from(recordsTable.children);
            records.forEach(record => {
                const text = record.textContent.toLowerCase();
                if (text.includes(searchQuery)) {
                    record.style.display = 'table-row';
                } else {
                    record.style.display = 'none';
                }
            });
        });

        async function loadRecords() {
            try {
                const response = await fetch('../backend/طلاب.php');
                const data = await response.json();
                recordsTable.innerHTML = '';
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.name}</td>
                        <td>${record.birthdate}</td>
                        <td>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                        <td>
                            <a href="edit_طلاب.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                        </td>
                    `;
                    recordsTable.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        loadRecords();

        async function deleteRecord(id) {
            try {
                const response = await fetch('../backend/طلاب.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                });
                if (response.ok) {
                    loadRecords();
                } else {
                    console.error('Error deleting record');
                }
            } catch (error) {
                console.error(error);
            }
        }
    </script>
</body>
</html>

**backend/طلاب.php**

<?php
// Assuming a database connection is established

// Retrieve all records
$records = array();
$query = "SELECT * FROM students";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $records[] = $row;
}

// Output records in JSON format
header('Content-Type: application/json');
echo json_encode($records);
?>

Note: This is a basic implementation and you should adapt it to your specific needs and database schema. Additionally, you should ensure proper error handling and security measures are in place.