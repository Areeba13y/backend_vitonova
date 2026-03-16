<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4fdf4; color: #333; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; padding: 30px; border-radius: 8px; border-top: 5px solid #22c55e; }
        h1 { color: #166534; }
        .details { background: #f0fdf4; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .footer { font-size: 12px; color: #666; margin-top: 30px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>New Team Application</h1>
        <p>A new person has requested to join the team.</p>
        <div class="details">
            <p><strong>Full Name:</strong> {{ $application->name }}</p>
            <p><strong>Email:</strong> {{ $application->email }}</p>
            <p><strong>Position:</strong> {{ $application->position }}</p>
        </div>
        <p>Please log in to the admin panel to review the application and download the resume.</p>
        <div class="footer">
            &copy; {{ date('Y') }} Vitonova. All rights reserved.
        </div>
    </div>
</body>
</html>
