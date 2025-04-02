<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
</head>
<body>
    <h2>Hello, {{ $customerName }}</h2>
    <p>Your booking has been confirmed:</p>
    <ul>
        <li><strong>Pickup Location:</strong> {{ $pickup }}</li>
        <li><strong>Dropoff Location:</strong> {{ $dropoff }}</li>
        <li><strong>Estimated Fare:</strong> ${{ $fare }}</li>
        <li><strong>Vehicle Type:</strong> {{ $vehicle }}</li>
        <li><strong>Scheduled Time:</strong> {{ $time }}</li>
    </ul>
    <p>Thank you for choosing our service!</p>
</body>
</html>
