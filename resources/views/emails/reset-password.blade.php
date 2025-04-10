<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body>
    <p>Hello,</p>

    <p>You requested to reset your password. Click the button below to proceed:</p>

    <p>
        <a href="{{ url('/reset-password/' . $token) }}"
           style="display: inline-block; background-color: #0d6efd; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            Reset Password
        </a>
    </p>

    <p>This link will expire in 60 minutes. If you didn't request a password reset, no action is needed.</p>

    <p>Regards,<br>{{ config('app.name') }} Team</p>
</body>
</html>
