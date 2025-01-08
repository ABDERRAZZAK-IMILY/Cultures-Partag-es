

<?php

require_once '../model/visteur.php';
require_once '../model/db_connect.php';




if(isset($_POST['login_submit'])){
    session_start();
    $c = new DATABASE();
    $conn = $c->getConnection();
    $login = new Visteur($conn);

 $email = $_POST['email'];
 $password = $_POST['password'];

 if ($login->login($email, $password)) {

 } else {
    echo "<script>
    window.onload = function() {
        Swal.fire({
            title: 'Error!',
            text: 'wrong email or password.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }
  </script>"; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <link rel="stylesheet" href="sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translatey(0px); }
            50% { transform: translatey(-20px); }
            100% { transform: translatey(0px); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-400 via-pink-500 to-red-500 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4" x-data="{ tab: 'login' }">
        <div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden shadow-2xl transform hover:scale-105 transition-transform duration-300">
            <div class="text-center py-6 bg-gradient-to-r from-blue-500 to-purple-600 text-white">
                <h1 class="text-3xl font-bold">Welcome</h1>
            </div>
            <div class="p-8">
                <div class="flex justify-center mb-6">
                    <button @click="tab = 'login'" :class="{ 'bg-blue-500 text-white': tab === 'login', 'bg-gray-200 text-gray-700': tab !== 'login' }" class="px-4 py-2 rounded-r-md focus:outline-none transition-colors duration-300">Login</button>
                </div>
                <form x-show="tab === 'login'" method="POST" id="validation" class="space-y-4">
                    <div class="relative">
                        <input type="email" name="email" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pl-10" placeholder="Email" required>
                        <i class="fas fa-envelope absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <div class="relative">
                        <input type="password" name="password" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pl-10" placeholder="Password" required>
                        <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <button class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-2 rounded-md hover:opacity-90 transition-opacity duration-300 transform hover:scale-105" type="submit" name="login_submit">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const emailPattern = /^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,6}$/;
    const passwordPattern = /^(?=.*[a-zA-Z0-9]).{4,}$/;

    document.getElementById('validation').addEventListener('submit', function(event) {
        
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        if (!emailPattern.test(email)) {
            Swal.fire({
                title: 'Error!',
                text: 'Please enter a valid email address.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            event.preventDefault();
            return;
        }

        if (!passwordPattern.test(password)) {
            Swal.fire({
                title: 'Error!',
                text: 'Password must be at least 4 characters long.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            event.preventDefault();
            return;
        }
    });
</script>


</html>
