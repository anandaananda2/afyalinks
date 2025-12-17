<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $payment->transaction_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #f3f4f6;
        }
        .receipt {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #1f2937;
            margin: 0 0 10px 0;
        }
        .header p {
            color: #6b7280;
            margin: 5px 0;
        }
        .status {
            text-align: center;
            background: #10b981;
            color: white;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 30px;
            font-size: 18px;
            font-weight: bold;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            color: #374151;
            font-size: 18px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 15px;
        }
        .info-label {
            color: #6b7280;
            font-weight: 500;
        }
        .info-value {
            color: #1f2937;
            font-weight: 600;
        }
        .amount {
            text-align: center;
            background: #f0fdf4;
            padding: 20px;
            border-radius: 6px;
            margin: 30px 0;
        }
        .amount-label {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .amount-value {
            color: #059669;
            font-size: 36px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }
        .no-print button {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }
        .no-print button:hover {
            background: #2563eb;
        }
        @media print {
            body {
                background: white;
                margin: 0;
            }
            .no-print {
                display: none;
            }
            .receipt {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">🖨️ Print Receipt</button>
    </div>

    <div class="receipt">
        <div class="header">
            <h1>AFYALINKS HEALTH SYSTEM</h1>
            <p>Rural Healthcare Management</p>
            <p>Email: info@afyalinks.com | Phone: +254 700 000 000</p>
        </div>

        <div class="status">
            ✓ PAYMENT SUCCESSFUL
        </div>

        <div class="section">
            <h2>Payment Information</h2>
            <div class="info-grid">
                <div class="info-label">Transaction ID:</div>
                <div class="info-value">{{ $payment->transaction_id }}</div>

                <div class="info-label">Payment Date:</div>
                <div class="info-value">{{ $payment->paid_at ? $payment->paid_at->format('F d, Y h:i A') : $payment->created_at->format('F d, Y h:i A') }}</div>

                <div class="info-label">Payment Method:</div>
                <div class="info-value">{{ $payment->paymentMethodLabel }}</div>

                @if($payment->provider_transaction_id)
                <div class="info-label">Provider Reference:</div>
                <div class="info-value">{{ $payment->provider_transaction_id }}</div>
                @endif
            </div>
        </div>

        <div class="amount">
            <div class="amount-label">Amount Paid</div>
            <div class="amount-value">KES {{ number_format($payment->amount, 2) }}</div>
        </div>

        <div class="section">
            <h2>Patient Information</h2>
            <div class="info-grid">
                <div class="info-label">Name:</div>
                <div class="info-value">{{ $payment->appointment->patient->name }}</div>

                <div class="info-label">Email:</div>
                <div class="info-value">{{ $payment->appointment->patient->email }}</div>

                @if($payment->appointment->patient->phone)
                <div class="info-label">Phone:</div>
                <div class="info-value">{{ $payment->appointment->patient->phone }}</div>
                @endif
            </div>
        </div>

        <div class="section">
            <h2>Appointment Details</h2>
            <div class="info-grid">
                <div class="info-label">Appointment Number:</div>
                <div class="info-value">{{ $payment->appointment->appointment_number }}</div>

                <div class="info-label">Doctor:</div>
                <div class="info-value">Dr. {{ $payment->appointment->doctor->name }}</div>

                <div class="info-label">Specialization:</div>
                <div class="info-value">{{ $payment->appointment->doctor->doctorProfile->specialization }}</div>

                <div class="info-label">Appointment Date:</div>
                <div class="info-value">{{ $payment->appointment->appointment_date->format('F d, Y') }}</div>

                <div class="info-label">Appointment Time:</div>
                <div class="info-value">{{ $payment->appointment->appointment_time->format('h:i A') }}</div>

                <div class="info-label">Duration:</div>
                <div class="info-value">{{ $payment->appointment->duration }} minutes</div>

                <div class="info-label">Type:</div>
                <div class="info-value">{{ ucfirst($payment->appointment->type) }}</div>
            </div>
        </div>

        <div class="footer">
            <p>This is a computer-generated receipt and does not require a signature.</p>
            <p>For any queries, please contact us at info@afyalinks.com</p>
            <p>Thank you for choosing Afyalinks Health System!</p>
        </div>
    </div>
</body>
</html>