<!DOCTYPE html>
<html>
<head>
    <title>Password Reset Request</title>
</head>
<body>
    <p>Hello,</p>
    <p>You requested a password reset. Use the following code to reset your password:</p>
    <h2>{{ $resetCode }}</h2>
    <p>Or click the link below to reset your password:</p>
    <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
    <p>If you did not request this, please ignore this email.</p>
    <p>Thank you!</p>
</body>
</html>
