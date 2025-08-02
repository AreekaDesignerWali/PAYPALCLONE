<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch received transactions
$stmt = $pdo->prepare("SELECT t.*, u.username as sender_name FROM transactions t JOIN users u ON t.sender_id = u.id WHERE t.receiver_id = ? AND t.transaction_type = 'send' ORDER BY t.created_at DESC");
$stmt->execute([$user_id]);
$received_transactions = $stmt->fetchAll();

// Handle money request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sender_email = $_POST['sender_email'];
    $amount = $_POST['amount'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$sender_email]);
    $sender = $stmt->fetch();

    if ($sender && $amount > 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO transactions (sender_id, receiver_id, amount, transaction_type, status) VALUES (?, ?, ?, 'send', 'pending')");
            $stmt->execute([$sender['id'], $user_id, $amount]);
            $success = "Money request sent successfully!";
        } catch (PDOException $e) {
            $error = "Error sending request: " . $e->getMessage();
        }
    } else {
        $error = "Invalid sender email or amount";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receive Money - PayPal Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #0070ba;
            padding: 15px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .receive-money-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .request-form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .request-form h2 {
            color: #0070ba;
            text-align: center;
        }
        .request-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .request-form button {
            width: 100%;
            padding: 10px;
            background-color: #0070ba;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .request-form button:hover {
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
        .transactions {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .transactions h2 {
            color: #0070ba;
        }
        .transactions table {
            width: 100%;
            border-collapse: collapse;
        }
        .transactions th, .transactions td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        .transactions th {
            background-color: #0070ba;
            color: white;
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
            .navbar {
                flex-direction: column;
            }
            .request-form {
                padding: 15px;
            }
            .transactions table {
                font-size: 14px;
            }
            .receive-money-container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div>PayPal Clone</div>
        <div>
            <a href="#" onclick="redirectTo('dashboard.php')">Dashboard</a>
            <a href="#" onclick="redirectTo('logout.php')">Logout</a>
        </div>
    </div>
    <div class="receive-money-container">
        <div class="request-form">
            <h2>Request Money</h2>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
            <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
            <form method="POST">
                <input type="email" name="sender_email" placeholder="Sender's Email" required>
                <input type="number" name="amount" placeholder="Amount" step="0.01" required>
                <button type="submit">Send Request</button>
            </form>
            <div class="link">
                <p><a href="#" onclick="redirectTo('dashboard.php')">Back to Dashboard</a></p>
            </div>
        </div>
        <div class="transactions">
            <h2>Received Transactions</h2>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Sender</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
                <?php foreach ($received_transactions as $t): ?>
                    <tr>
                        <td><?php echo $t['created_at']; ?></td>
                        <td><?php echo $t['sender_name']; ?></td>
                        <td>$<?php echo number_format($t['amount'], 2); ?></td>
                        <td><?php echo $t['status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
