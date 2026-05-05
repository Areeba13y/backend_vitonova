<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4fdf4; color: #333; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; padding: 30px; border-radius: 8px; border-top: 5px solid #22c55e; }
        h1 { color: #166534; }
        .footer { font-size: 12px; color: #666; margin-top: 30px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registration Received</h1>
        <p>Hi {{ $user->name }},</p>
        <p>Thank you for registering for <strong>{{ $event->title }}</strong>. We have received your event form successfully.</p>
        <p>Event date: <strong>{{ $event->event_date?->format('Y-m-d') }}</strong></p>
        <p>We will contact you if any additional details are required.</p>
        <p>Best regards,<br>Vitonova Team</p>
        <div class="footer">
            &copy; {{ date('Y') }} Vitonova. All rights reserved.
        </div>
    </div>
</body>
</html>
