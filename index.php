<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayPal Clone - Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .navbar {
            background-color: #0070ba;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .hero {
            text-align: center;
            padding: 50px 20px;
            background: linear-gradient(135deg, #0070ba, #00a1d6);
            color: white;
        }
        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }
        .hero p {
            font-size: 20px;
            margin-bottom: 30px;
        }
        .btn {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #218838;
        }
        .features {
            display: flex;
            justify-content: space-around;
            padding: 50px 20px;
            flex-wrap: wrap;
        }
        .feature-card {
            background: white;
            padding: 20px;
            margin: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .feature-card h3 {
            color: #0070ba;
        }
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
            }
            .navbar a {
                margin: 5px 0;
            }
            .hero h1 {
                font-size: 32px;
            }
            .hero p {
                font-size: 16px;
            }
            .feature-card {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div>PayPal Clone</div>
        <div>
            <a href="#" onclick="redirectTo('login.php')">Login</a>
            <a href="#" onclick="redirectTo('signup.php')">Sign Up</a>
        </div>
    </div>
    <div class="hero">
        <h1>Welcome to PayPal Clone</h1>
        <p>Send, receive, and manage your money securely and easily.</p>
        <a href="#" onclick="redirectTo('signup.php')" class="btn">Get Started</a>
    </div>
    <div class="features">
        <div class="feature-card">
            <h3>Send Money</h3>
            <p>Transfer funds to anyone using their email or username instantly.</p>
        </div>
        <div class="feature-card">
            <h3>Secure Wallet</h3>
            <p>Store and manage your funds with top-notch security.</p>
        </div>
        <div class="feature-card">
            <h3>Transaction History</h3>
            <p>Track all your transactions with detailed history.</p>
        </div>
    </div>
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
