<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>All Payments</title>
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
            font-size: 12px;
        }
        th {
            background-color: #f2f2f2;
        }
        .totals {
            text-align: right;
            margin: 20px 0;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ALL PAYMENTS REPORT</h1>
            <h3>Laovieng College</h3>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Student</th>
                    <th>Major</th>
                    <th>Base Amount</th>
                    <th>Discount %</th>
                    <th>Final Amount</th>
                    <th>Employee</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($payment->date)->format('d/m/Y') }}</td>
                    <td>{{ $payment->student->name }} {{ $payment->student->sername }}</td>
                    <td>{{ $payment->major->name }}</td>
                    <td>{{ number_format($payment->detail_price, 2) }}</td>
                    <td>{{ $payment->pro }}%</td>
                    <td>{{ number_format($payment->total_price, 2) }}</td>
                    <td>{{ $payment->employee ? $payment->employee->name : 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6" style="text-align: right">Total:</th>
                    <th>{{ number_format($total, 2) }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
