<?php
session_start();

// Check if user is authenticated
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
    <title>نظام إدارة مدارس</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .glassmorphism-card {
            background-color: #f7f7f7;
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="flex justify-between items-center p-4 bg-slate-900 text-white">
        <h1 class="text-3xl font-bold">نظام إدارة مدارس</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
    </div>
    <div class="glassmorphism-card mx-auto w-4/5 p-4">
        <h2 class="text-2xl font-bold mb-4">مرحباً بكم في النظام</h2>
        <div class="flex flex-wrap justify-center mb-4">
            <div class="w-full md:w-1/2 xl:w-1/3 p-4">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold mb-2">إحصائيات النظام</h3>
                    <div id="stats-grid" class="flex flex-wrap justify-center"></div>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap justify-center mb-4">
            <div class="w-full md:w-1/2 xl:w-1/3 p-4">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold mb-2">إدارة الطلاب</h3>
                    <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='students.php'">إدارة الطلاب</button>
                </div>
            </div>
            <div class="w-full md:w-1/2 xl:w-1/3 p-4">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold mb-2">إدارة المعلمين</h3>
                    <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='teachers.php'">إدارة المعلمين</button>
                </div>
            </div>
            <div class="w-full md:w-1/2 xl:w-1/3 p-4">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold mb-2">إدارة الدرجات</h3>
                    <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='grades.php'">إدارة الدرجات</button>
                </div>
            </div>
            <div class="w-full md:w-1/2 xl:w-1/3 p-4">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold mb-2">إدارة المواعيد</h3>
                    <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='schedules.php'">إدارة المواعيد</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fetch stats dynamically via API calls
        fetch('api/stats.php')
            .then(response => response.json())
            .then(data => {
                const statsGrid = document.getElementById('stats-grid');
                data.forEach(stat => {
                    const statCard = document.createElement('div');
                    statCard.classList.add('bg-white', 'rounded-lg', 'shadow-md', 'p-4', 'w-full', 'md:w-1/2', 'xl:w-1/3');
                    statCard.innerHTML = `
                        <h3 class="text-lg font-bold mb-2">${stat.title}</h3>
                        <p class="text-lg font-bold mb-2">${stat.value}</p>
                    `;
                    statsGrid.appendChild(statCard);
                });
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code uses Tailwind CSS for styling and includes a glassmorphism card layout with a premium design. It also includes a session check to redirect to the login page if the user is not authenticated. The dashboard layout includes a welcome message, logout button, overview stats grid, and quick links to manage modules. The stats grid is populated dynamically via API calls from the backend files.