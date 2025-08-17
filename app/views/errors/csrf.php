<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Security Check Failed</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: #fafafa;
            font-family: Arial, sans-serif;
            color: #333;
            text-align: center;
            padding: 50px;
        }
        .error-box {
            background: #fff;
            border: 1px solid #ddd;
            display: inline-block;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h1 {
            color: #c0392b;
        }
        p {
            margin: 20px 0;
        }
        a.button {
            display: inline-block;
            background: #c0392b;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
        }
        a.button:hover {
            background: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="error-box">
        <h1>Security Verification Failed</h1>
        <p>Your session token was invalid or expired.<br>
           Please go back and try submitting the form again.</p>
        <p>
            <a href="javascript:history.back()" class="button">Go Back</a>
        </p>
    </div>
</body>
</html>
