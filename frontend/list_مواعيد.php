**list_مواعيد.php**

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
    <title>مواعيد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d3748;
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
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #2d3748;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            font-size: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar:focus {
            outline: none;
            border-color: #aaa;
        }
        .btn {
            background-color: #2d3748;
            color: #fff;
            padding: 1rem 2rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #3b4453;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span>مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">مواعيد</h1>
        <div class="flex justify-between mb-4">
            <button class="btn" onclick="location.href='create_مواعيد.php'">إضافة جديد</button>
            <input type="search" class="search-bar" id="search" placeholder="بحث...">
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>التاريخ</th>
                    <th>الوقت</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $url = '../backend/مواعيد.php';
                $response = fetchRecords($url);
                if ($response) {
                    foreach ($response as $record) {
                        echo '<tr>';
                        echo '<td>' . $record['name'] . '</td>';
                        echo '<td>' . $record['date'] . '</td>';
                        echo '<td>' . $record['time'] . '</td>';
                        echo '<td>';
                        echo '<a href="edit_مواعيد.php?id=' . $record['id'] . '">تعديل</a> ';
                        echo '<button class="btn" onclick="deleteRecord(' . $record['id'] . ')">حذف</button>';
                        echo '</td>';
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function fetchRecords(url) {
            return fetch(url)
                .then(response => response.json())
                .then(data => data.records)
                .catch(error => console.error('Error:', error));
        }

        function deleteRecord(id) {
            fetch('../backend/مواعيد.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error deleting record');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const records = document.getElementById('records');
            const rows = records.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell.textContent.toLowerCase().includes(searchValue)) {
                        match = true;
                        break;
                    }
                }
                if (match) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>

<?php
function fetchRecords($url) {
    $response = file_get_contents($url);
    return json_decode($response, true);
}
?>

This code includes the following features:

*   Session validation to ensure the user is logged in before accessing the page.
*   A premium Tailwind UI layout with a header navigation, current user info, and logout link.
*   A table displaying a list of records with actions to edit and delete each record.
*   An "Add New Item" button linking to the `create_مواعيد.php` page.
*   A search bar filtering elements in real-time.
*   AJAX JavaScript code using the Fetch API to fetch records from the backend and delete records.
*   PHP code to fetch records from the backend and display them in the table.

Note that this code assumes you have a backend PHP script (`مواعيد.php`) that handles GET and DELETE requests to fetch and delete records, respectively. You will need to create this script separately.