<?php

require_once '../model/db_connect.php';

require_once '../model/admin.php';

session_start();

$conn = (new DATABASE())->getConnection();


$A = new Admin($conn);

$userprofile = [];

$query = "SELECT * FROM users where id != 2 ";
$stmt = $conn->query($query);

if ($stmt && $stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $userprofile[] = $row;
    }
} else {
    echo "No users found.";
}

if (isset($_GET['ban_user_id'])) {
    $userId = $_GET['ban_user_id'];

    
$bannuser = $A->BannUsers($userId);


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Users Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="overflow-x-auto p-4 bg-white rounded-lg shadow-md">
    <table class="table-auto w-full border-collapse border border-gray-200">
        <thead class="bg-orange-700 text-white">
            <tr>
                <th class="px-4 py-2 border border-gray-300">First Name</th>
                <th class="px-4 py-2 border border-gray-300">Last Name</th>
                <th class="px-4 py-2 border border-gray-300">Email</th>
                <th class="px-4 py-2 border border-gray-300">Status</th>
                <th class="px-4 py-2 border border-gray-300">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($userprofile as $user): ?>
            <tr class="odd:bg-purple-50 even:bg-purple-100 hover:bg-purple-200">
                <td class="px-4 py-2 border border-gray-300"><?= htmlspecialchars($user['firstName']) ?></td>
                <td class="px-4 py-2 border border-gray-300"><?= htmlspecialchars($user['lastName']) ?></td>
                <td class="px-4 py-2 border border-gray-300"><?= htmlspecialchars($user['email']) ?></td>
                <td class="px-4 py-2 border border-gray-300">
                    <?= htmlspecialchars($user['status']) ?>
                </td>
                <td class="px-4 py-2 border border-gray-300">
                    <?php if ($user['status'] == 'active'): ?>
                        <a href="?ban_user_id=<?= $user['id'] ?>" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Ban account
                        </a>
                    <?php else: ?>
                        <span class="text-gray-500">Banned</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
