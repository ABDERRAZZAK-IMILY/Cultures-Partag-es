
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



<div class="overflow-x-auto p-4 bg-white rounded-lg shadow-md">
    <table class="table-auto w-full border-collapse border border-gray-200">
        <thead class="bg-orange-700 text-white">
            <tr>
                <th class="px-4 py-2 border border-gray-300">frist name</th>
                <th class="px-4 py-2 border border-gray-300">last name</th>
                <th class="px-4 py-2 border border-gray-300">email</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($userprofile as $userprofiles): ?>
            <tr class="odd:bg-purple-50 even:bg-purple-100 hover:bg-purple-200">
                <td class="px-4 py-2 border border-gray-300"><?= htmlspecialchars($userprofiles['firstName']) ?></td>
                <td class="px-4 py-2 border border-gray-300"><?= htmlspecialchars($userprofiles['lastName']) ?></td>
                <td class="px-4 py-2 border border-gray-300"><?= htmlspecialchars($userprofiles['email']) ?></td>
            </tr>
        <?php endforeach; ?>
           
    </table>
</div>


    
</body>
</html>