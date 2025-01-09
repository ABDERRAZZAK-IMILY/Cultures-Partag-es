<?php
session_start();

$dsn = 'mysql:host=localhost;dbname=blog';
$username = 'root';
$password = '';

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    try {
        $A = new PDO($dsn, $username, $password);
        $A->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $A->prepare("SELECT * FROM users WHERE id = :userId");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            die("Error fetching user data.");
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    header('Location: login.php');
    exit();
}
?>

<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Profile Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        .profile-picture-preview {
            max-width: 100px;
            max-height: 100px;
            margin-top: 10px;
            border-radius: 50%;
        }
    </style>
</head>
<body class="bg-gray-100 font-roboto">
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="flex items-center p-6 bg-blue-500">
            <img alt="Profile picture" class="w-24 h-24 rounded-full border-4 border-white" height="100" src="../assest/upload/<?php echo htmlspecialchars($userData['image']); ?>" width="100"/>
                <div class="ml-4">
                    <h1 class="text-white text-2xl font-bold">
                        <?php echo htmlspecialchars($userData['firstName'] . ' ' . $userData['lastName']); ?>
                    </h1>
                    <p class="text-white">
                        <?php echo htmlspecialchars($userData['email']); ?>
                    </p>
                    <p class="text-white">
                        <?php echo htmlspecialchars($userData['role']); ?>
                    </p>
                </div>
            </div>
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-4">
                    Modify Profile
                </h2>
                <form action="profile.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                            Username
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" placeholder="Username" type="text" value="<?php echo htmlspecialchars($userData['firstName'] . ' ' . $userData['lastName']); ?>" />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                            Email
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" placeholder="Email" type="email" value="<?php echo htmlspecialchars($userData['email']); ?>" readonly/>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="role">
                            Role
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="role" placeholder="Role" type="text" value="<?php echo htmlspecialchars($userData['role']); ?>" readonly/>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="profile-picture">
                            Profile Picture
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="profile-picture" name="profile_picture" type="file">
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
