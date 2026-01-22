<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Confirmation</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .details { margin-bottom: 20px; }
        .label { font-weight: bold; }
        .footer { margin-top: 50px; font-size: 12px; text-align: center; color: #666; }
        .status-badge { 
            background-color: #f3f4f6; 
            padding: 5px 10px; 
            border-radius: 15px; 
            font-size: 12px;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Appointment Confirmation</h1>
        <p>Reference: #{{ $appointment->appointment_number }}</p>
    </div>

    <div class="details">
        <p>Dear {{ $appointment->patient->name }},</p>
        <p>Your appointment has been successfully booked. Please find the details below:</p>

        <table>
            <tr>
                <th>Doctor:</th>
                <td>Dr. {{ $appointment->doctor->name }}</td>
            </tr>
            <tr>
                <th>Date:</th>
                <td>{{ $appointment->appointment_date->format('l, F d, Y') }}</td>
            </tr>
            <tr>
                <th>Time:</th>
                <td>{{ $appointment->appointment_time->format('h:i A') }}</td>
            </tr>
            <tr>
                <th>Type:</th>
                <td>{{ ucfirst($appointment->type) }}</td>
            </tr>
            <tr>
                <th>Consultation Fee:</th>
                <td>KES {{ number_format($appointment->consultation_fee, 2) }}</td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 30px; padding: 15px; background-color: #fffbeb; border: 1px solid #fcd34d; border-radius: 5px;">
        <strong>Important Note:</strong>
        <p style="margin: 5px 0 0 0;">Payment will be made on the appointment day before seeing the doctor.</p>
    </div>

    <div class="footer">
        <p>Thank you for choosing Afyalinks.</p>
        <p>If you need to reschedule or cancel, please contact us at least 2 hours in advance.</p>
    </div>
</body>
</html>
