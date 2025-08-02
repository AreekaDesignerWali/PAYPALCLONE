<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$stmt = $pdo->prepare("SELECT t.*, u.username as receiver_name FROM transactions t JOIN users u ON t.receiver_id = u.id WHERE t.sender_id = ? OR t.receiver_id = ? ORDER BY t.created_at DESC");
$stmt->execute([$user_id, $user_id]);
$transactions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PayPal Clone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
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
        .dashboard {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .balance {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 20px;
        }
        .balance h2 {
            color: #0070ba;
        }
        .actions {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .action-btn {
            padding: 10px 20px;
            background-color: #0070ba;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px;
        }
        .action-btn:hover {
            background-color: #005ea6;
        }
        .transactions {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
            }
            .actions {
                flex-direction: column;
                align-items: center;
            }
            .action-btn {
                width: 100%;
                max-width: 300px;
            }
            .transactions table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div>PayPal Clone</div>
        <div>
            <a href="#" onclick="redirectTo('logout.php')">Logout</a>
        </div>
    </div>
    <div class="dashboard">
        <div class="balance">
            <h2>Balance: $<?php echo number_format($user['balance'], 2); ?></h2>
        </div>
        <div class="actions">
            <button class="action-btn" onclick="redirectTo('send_money.php')">Send Money</button>
            <button class="action-btn" onclick="redirectTo('add_funds.php')">Add Funds</button>
        </div>
        <div class="transactions">
            <h2>Transaction History</h2>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Receiver</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
                <?php foreach ($transactions as $t): ?>
                    <tr>
                        <td><?php echo $t['created_at']; ?></td>
                        <td><?php echo $t['transaction_type']; ?></td>
                        <td><?php echo $t['receiver_name']; ?></td>
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
