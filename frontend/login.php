<!-- login.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #1a1d23, #2c2f36);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s linear;
        }
        .glassmorphic {
            background: linear-gradient(90deg, #1a1d23 0%, #2c2f36 100%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .glassmorphic::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #1a1d23 0%, #2c2f36 100%);
            filter: blur(20px);
            z-index: -1;
        }
    </style>
</head>
<body class="bg-gray-900 h-screen flex justify-center items-center">
    <div class="glassmorphic bg-gradient-to-br from-slate-900 to-indigo-500 p-10 rounded-lg shadow-lg w-96">
        <h1 class="text-3xl font-bold text-white mb-5">Login</h1>
        <form id="login-form">
            <div class="mb-4">
                <label for="username" class="block text-white text-sm mb-2">Username</label>
                <input type="text" id="username" name="username" class="block w-full p-2 text-white bg-gray-800 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                <div id="username-error" class="text-red-500 hidden"></div>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-white text-sm mb-2">Password</label>
                <input type="password" id="password" name="password" class="block w-full p-2 text-white bg-gray-800 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                <div id="password-error" class="text-red-500 hidden"></div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Login</button>
            <p class="text-white text-sm mt-2">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-700">Register</a></p>
        </form>
    </div>

    <script>
        const form = document.getElementById('login-form');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const usernameError = document.getElementById('username-error');
        const passwordError = document.getElementById('password-error');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            usernameError.textContent = '';
            passwordError.textContent = '';
            const username = usernameInput.value.trim();
            const password = passwordInput.value.trim();
            if (username === '') {
                usernameError.textContent = 'Username is required';
                usernameError.classList.remove('hidden');
            } else if (password === '') {
                passwordError.textContent = 'Password is required';
                passwordError.classList.remove('hidden');
            } else {
                try {
                    const response = await fetch('../backend/auth.php?action=login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ username, password })
                    });
                    const data = await response.json();
                    if (data.success) {
                        window.location.href = 'dashboard.php';
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    console.error(error);
                    alert('Error logging in. Please try again later.');
                }
            }
        });
    </script>
</body>
</html>


This code creates a premium-looking login page with a glassmorphic layout, gradients, and a form for username and password input. It uses the Tailwind CSS CDN for styling and includes a beautiful glassmorphic layout with gradients. The form includes standard HTML input pattern validators to support Arabic and Latin characters. The JavaScript code uses the Fetch API to submit the credentials to the backend and handle the response or error alerts dynamically. The page also includes a direct link to the register.php page.