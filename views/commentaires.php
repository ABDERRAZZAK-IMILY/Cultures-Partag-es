<?php

require_once '../model/db_connect.php';

session_start();

$conn = (new DATABASE())->getConnection();

$commentairs = [];

$query = "SELECT * FROM comments";
$stmt = $conn->query($query);

if ($stmt && $stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $commentairs[] = $row;
    }
} else {
    echo "no comment found.";
}

if (isset($_GET['ban_user_id'])) {
    $userId = $_GET['ban_user_id'];

    $deleteQuery = "DELETE  content from  comments WHERE id = :id";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bindParam(':id', $userId, PDO::PARAM_INT);
    if ($deleteStmt->execute()) {
        echo "<script>
        window.onload = function() {
            Swal.fire({
                title: 'Success!',
                text: 'User has been banned',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }
      </script>"; 

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Failed to ban user.";
    }
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
                <th class="px-4 py-2 border border-gray-300">commentair</th>            
                <th class="px-4 py-2 border border-gray-300">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($commentairs as $commentair): ?>
            <tr class="odd:bg-purple-50 even:bg-purple-100 hover:bg-purple-200">
                <td class="px-4 py-2 border border-gray-300"><?= htmlspecialchars($commentair['content']) ?></td>
               
                <td class="px-4 py-2 border border-gray-300">
                        <a href="?ban_user_id=<?= $commentair['id'] ?>" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            remove comment
                        </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
