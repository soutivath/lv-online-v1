<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Payment Bill</title>
    <link href="https://fonts.googleapis.com/css2?family=Phetsarath:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Phetsarath', sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .bill-info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .totals {
            text-align: right;
            margin: 20px 0;
        }
        .qr-code {
            text-align: center;
            margin-top: 30px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
        }
        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #000;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PAYMENT BILL</h1>
            <h3>Laovieng College</h3>
        </div>
        
        <div class="bill-info">
            <p><strong>Bill No:</strong> {{ $payment->id }}</p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($payment->date)->format('d/m/Y H:i') }}</p>
            <p><strong>Student ID:</strong> {{ $payment->student->id }}</p>
            <p><strong>Student Name:</strong> {{ $payment->student->name }} {{ $payment->student->sername }}</p>
            <p><strong>Major:</strong> {{ $payment->major->name }}</p>
            <p><strong>Received By:</strong> {{ $payment->employee ? $payment->employee->name . ' ' . $payment->employee->sername : 'N/A' }}</p>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Discount</th>
                    <th>Final Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $payment->major->name }} - {{ $payment->major->semester->name }} 
                        {{ $payment->major->term->name }} {{ $payment->major->year->name }}</td>
                    <td>{{ number_format($payment->detail_price, 2) }}</td>
                    <td>{{ $payment->pro }}% ({{ number_format($payment->detail_price - $payment->total_price, 2) }})</td>
                    <td>{{ number_format($payment->total_price, 2) }}</td>
                </tr>
            </tbody>
        </table>
        
        <div class="totals">
            <p><strong>Total Amount: {{ number_format($payment->total_price, 2) }}</strong></p>
        </div>
        
        <div class="qr-code">
            <img src="data:image/png;base64,{{ $qrCode }}" alt="Payment QR Code">
            <p>Scan to verify payment</p>
        </div>
        
      
        
      
    </div>
</body>
</html>
