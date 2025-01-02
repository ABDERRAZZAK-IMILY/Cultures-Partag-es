
<?php

require_once '../model/db_connect.php';

session_start();

$conn = (new DATABASE())->getConnection();

$userprofile = [];

$query = "SELECT * FROM users";
$stmt = $conn->query($query);

if ($stmt && $stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $userprofile[] = $row;
    }
} else {
    echo "no users found.";
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>list users profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<div class="flex-wrap flex justify-between">
<?php foreach ($userprofile as $userprofiles): ?>
<div
    class="py-8 px-8 max-w-sm mx-auto bg-white rounded-xl shadow-lg space-y-2 sm:py-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-6">
    <img class="block mx-auto h-24 rounded-full sm:mx-0 sm:shrink-0" src="https://tailwindcss.com/img/erin-lindford.jpg" alt="Woman's Face">
    <div class="text-center space-y-2 sm:text-left">
        <div class="space-y-0.5">
            <p class="text-lg text-black font-semibold">
                <?php  $userprofiles['firstName']  ?>                  <?php  $userprofiles['lastName']  ?>
            </p>
            <p class="text-slate-500 font-medium">
            <?php  $userprofiles['email']  ?> 
            </p>
        </div>
        <button class="px-4 py-1 text-sm text-purple-600 font-semibold rounded-full border border-purple-200 hover:text-white hover:bg-purple-600 hover:border-transparent focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-offset-2">Message</button>
    </div>
</div>
<?php endforeach ?>
</div>

    
</body>
</html>