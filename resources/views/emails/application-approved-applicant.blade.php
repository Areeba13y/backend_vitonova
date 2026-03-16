<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4fdf4; color: #333; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; padding: 30px; border-radius: 8px; border-top: 5px solid #22c55e; }
        h1 { color: #166534; }
        .success-box { background: #dcfce7; color: #166534; padding: 20px; border-radius: 8px; text-align: center; font-weight: bold; margin: 20px 0; }
        .footer { font-size: 12px; color: #666; margin-top: 30px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Congratulations!</h1>
        <p>Hi {{ $application->name }},</p>
        <div class="success-box">
            Your application to join the Vitonova team has been approved!
        </div>
        <p>We are excited to have you with us for the <strong>{{ $application->position }}</strong> position. We will reach out to you shortly with more details and next steps.</p>
        <p>Welcome aboard!<br>Vitonova Team</p>
        <div class="footer">
            &copy; {{ date('Y') }} Vitonova. All rights reserved.
        </div>
    </div>
</body>
</html>
