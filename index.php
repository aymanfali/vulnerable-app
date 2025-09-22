<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $comment_text = trim($_POST['comment_text'] ?? '');

    if ($username && $comment_text) {
        $sql = "INSERT INTO comments (username, comment_text) VALUES (:username, :comment_text)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':username'     => $username,
            ':comment_text' => $comment_text,
        ]);

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $error = "Both fields are required!";
    }
}

$stmt = $pdo->query("SELECT * FROM comments ORDER BY created_at DESC");
$comments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Vulnerable Comment App (mysqli)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 700px;
            margin: 40px auto;
        }

        textarea {
            width: 100%;
            height: 80px;
        }

        .comment {
            border-bottom: 1px solid #ddd;
            padding: 8px 0;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>
    <h2>Leave a Comment</h2>

    <?php if (!empty($error)) : ?>
        <p class="error"><?= htmlspecialchars($error, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Comment:</label><br>
        <textarea name="comment_text" required></textarea><br><br>

        <button type="submit">Submit</button>
    </form>

    <h3>Comments:</h3>
    <?php if ($comments): ?>
        <?php foreach ($comments as $comment): ?>
            <div class="comment">
                <!-- Escape all output to prevent XSS -->
                <strong><?= htmlspecialchars($comment['username'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></strong>
                <em>(<?= htmlspecialchars($comment['created_at'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>)</em>
                <p><?= nl2br(htmlspecialchars($comment['comment_text'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No comments yet.</p>
    <?php endif; ?>
</body>

</html>
