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

        $articlelikes =[];

        $stmt2 = $A->prepare("SELECT * FROM likes join article on likes.article_id = article.id");
        $stmt2->execute();

        if ($stmt2 && $stmt2->rowCount() > 0) {
            while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                $articlelikes[] = $row;
            }
        }
        // $articlelikes = $stmt2->fetch((PDO::FETCH_ASSOC));
 

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



        <!-- our services section -->
<section class="py-10" id="services">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">liked article</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <?php  foreach($articlelikes as $articlelike) : ;  ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <img class="object-cover w-full h-52" src="<?= htmlspecialchars($articlelike['image']) ?: 'https://source.unsplash.com/200x200/?fashion' ?>" alt="Article Image">
                <div class="p-6 text-center">
                <p class="text-sm text-gray-500"><?= date("F j, Y", strtotime($articlelike['date_creation'])) ?></p>
                <h1     class="text-gray-900 font-semibold text-xl mt-2 mb-3"><?= htmlspecialchars($articlelike['title']) ?></h1>
                <p class="text-gray-700 text-base"><?= htmlspecialchars($articlelike['description']) ?></p>
                </div>
            </div>

                 <?php endforeach;   ?>


            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="https://images.unsplash.com/photo-1606854428728-5fe3eea23475?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8Z3JhbSUyMGZsb3VyfGVufDB8fDB8fHww" alt="Coffee"
                    class="w-full h-64 object-cover">
                <div class="p-6 text-center">
                    <h3 class="text-xl font-medium text-gray-800 mb-2">Gram Flour Grinding</h3>
                    <p class="text-gray-700 text-base">Our gram flour is perfect for a variety of uses, including
                        baking, cooking, and making snacks. It is also a good source of protein and fiber.Our gram flour
                        grinding service is a convenient and affordable way to get the freshest gram flour possible.</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="https://image2.jdomni.in/banner/13062021/D2/99/0D/48D7F4AFC48C041DC8D80432E9_1623562146900.png?output-format=webp" alt="Coffee"
                    class="w-full h-64 object-cover">
                <div class="p-6 text-center">
                    <h3 class="text-xl font-medium text-gray-800 mb-2">Jowar Flour Grinding</h3>
                    <p class="text-gray-700 text-base">Our jowar grinding service is a convenient and affordable way to
                        get fresh, high-quality jowar flour. We use state-of-the-art equipment to grind jowar into a
                        fine powder, which is perfect for making roti, bread, and other dishes.
                    <details>
                        <summary>Read More</summary>
                        <p>Our jowar flour is also
                            a good source of protein and fiber, making it a healthy choice for your family.</p>
                    </details>
                    </p>

                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="https://images.unsplash.com/photo-1607672632458-9eb56696346b?q=80&w=1914&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Coffee"
                    class="w-full h-64 object-cover">
                <div class="p-6 text-center">
                    <h3 class="text-xl font-medium text-gray-800 mb-2">Chilli pounding</h3>
                    <p class="text-gray-700 text-base">We specializes in the production of high-quality chili powder.
                        Our chili powder is made from the finest, freshest chilies, and we use traditional pounding
                        methods to ensure that our chili powder retains its full flavor and aroma.
                    <details>
                        <summary>Read More</summary>
                        <p> We offer a variety of chili powder products, including mild, medium, and hot. We also offer
                            custom blends to meet the specific needs of our customers.</p>
                    </details>
                    </p>
                </div>
            </div>
                </article>
            </div>
    </div>

</body>
</html>
