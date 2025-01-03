<?php
require_once '../model/USER.php';
require_once '../model/db_connect.php';
require_once '../model/Visteur.php';

if (isset($_POST['submit'])) {
    $firstName = $_POST['fristname'];
    $lastName = $_POST['lastname'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['password'];

        $c = new DATABASE();
        $conn = $c->getConnection();
        $visiteur = new Visteur($conn);

        if ($visiteur->register($firstName, $lastName, $email, $password, $role)) {
            echo "<script>
            window.onload = function() {
                Swal.fire({
                    title: 'Success!',
                    text: 'you are regestered.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            }
          </script>";        } else {
            echo "<script>
            window.onload = function() {
                Swal.fire({
                    title: 'Error!',
                    text: 'failed to regertred.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
          </script>";        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ultimate Signup Page</title>
    <script src="sweetalert2.min.js"></script>
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
    <div class="container mx-auto px-4" x-data="{ tab: 'signup' }">
        <div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden shadow-2xl transform hover:scale-105 transition-transform duration-300">
            <div class="text-center py-6 bg-gradient-to-r from-blue-500 to-purple-600 text-white">
                <h1 class="text-3xl font-bold">Welcome</h1>
                <p class="mt-2">Join our amazing community</p>
            </div>
            <div class="p-8">
                <form class="space-y-4" method="POST">
                    <div class="relative">
                        <input type="text" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pl-10" placeholder="Frist Name" name="fristname" required>
                        <i class="fas fa-user absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <div class="relative">
                        <input type="text" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pl-10" placeholder="Last Name" name="lastname" required>
                        <i class="fas fa-user absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <div class="relative">
                        <select class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pl-10" name="role" required>
                            <option value="visteur">Visteur</option>
                            <option value="Auteur">Auteur</option>
                        </select>
                        <i class="fas fa-user absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <div class="relative">
                        <input type="email" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pl-10" placeholder="Email" name="email" required>
                        <i class="fas fa-envelope absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <div class="relative">
                        <input type="password" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pl-10" placeholder="Password" name="password" required>
                        <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <button class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-2 rounded-md hover:opacity-90 transition-opacity duration-300 transform hover:scale-105" type="submit" name="submit">Sign Up</button>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>
