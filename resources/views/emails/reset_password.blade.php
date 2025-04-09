<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
</head>
<body>
    <p>Hello,</p>

    <p>You requested a password reset for your account. Click the link below to reset your password:</p>

    <p>
        <a href="{{ url('/reset-password/' . $token) }}">
            Reset Password
        </a>
    </p>

    <p>If you did not request this, please ignore this email.</p>

    <p>Thanks,<br>Your App Team</p>
</body>
</html>
