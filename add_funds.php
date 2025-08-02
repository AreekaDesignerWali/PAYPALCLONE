<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'];
    $user_id = $_SESSION['user_id'];

    if ($amount > 0) {
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
            $stmt->execute([$amount, $user_id]);

            $stmt = $pdo->prepare("INSERT INTO transactions (sender_id, receiver_id, amount, transaction_type, status) VALUES (?, ?, ?, 'add_funds', 'completed')");
            $stmt->execute([$user_id, $user_id, $amount]);

            $pdo->commit();
            $success = "Funds added successfully!";
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Failed to add funds: " . $e->getMessage();
        }
    } else {
        $error = "Invalid amount";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Funds - PayPal Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .add-funds-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .add-funds-container h2 {
            text-align: center;
            color: #0070ba;
        }
        .add-funds-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .add-funds-container button {
            width: 100%;
            padding: 10px;
            background-color: #0070ba;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .add-funds-container button:hover {
            background-color: #005ea6;
        }
        .error {
            color: red;
            text-align: center;
        }
        .success {
            color: green;
            text-align: center;
        }
        .link {
            text-align: center;
            margin-top: 10px;
        }
        .link a {
            color: #0070ba;
            text-decoration: none;
        }
        .link a:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .add-funds-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="add-funds-container">
        <h2>Add Funds</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        <form method="POST">
            <input type="number" name="amount" placeholder="Amount" step="0.01" required>
            <button type="submit">Add Funds</button>
        </form>
        <div class="link">
            <p><a href="#" onclick="redirectTo('dashboard.php')">Back to Dashboard</a></p>
        </div>
    </div>
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
