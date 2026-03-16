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
        <h1>Application Received</h1>
        <p>Hi {{ $application->name }},</p>
        <p>Thank you for your interest in joining the Vitonova team! Your application for the <strong>{{ $application->position }}</strong> position has been successfully submitted.</p>
        <p>Our team will review your application and get back to you soon.</p>
        <p>Best regards,<br>Vitonova Team</p>
        <div class="footer">
            &copy; {{ date('Y') }} Vitonova. All rights reserved.
        </div>
    </div>
</body>
</html>
