<!DOCTYPE html>
<html>
<head>
    <title>Low Stock Alert</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color: #ff6b6b;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            color: #333333;
        }
        .content h1 {
            margin-top: 0;
        }
        .content p {
            line-height: 1.6;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #777777;
        }
        .footer a {
            color: #777777;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Low Stock Alert</h1>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>The product <strong>{{ $product->name }}</strong> is running low on stock. Please take necessary actions to replenish the stock.</p>
            <p><strong>Current Stock:</strong> {{ $product->stock->quantity }}</p>
            <p><strong>Minimum Stock:</strong> 10</p>
            <p>Thank you for your attention to this matter.</p>
        </div>
        <div class="footer">
            <p>This is an automated notification. Please do not reply to this email.</p>
            <p>If you have any questions, contact <a href="mailto:support@yourcompany.com">support@yourcompany.com</a>.</p>
        </div>
    </div>
</body>
</html>