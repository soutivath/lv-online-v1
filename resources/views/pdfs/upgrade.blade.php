<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Grade Upgrade Receipt</title>
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
        .info-section {
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
            <h1>GRADE UPGRADE RECEIPT</h1>
            <h3>Laovieng College</h3>
        </div>
        
        <div class="info-section">
            <h4>Upgrade Information</h4>
            <table>
                <tr>
                    <th style="width: 30%;">Upgrade ID</th>
                    <td>{{ $upgrade->id }}</td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td>{{ \Carbon\Carbon::parse($upgrade->date)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>Employee</th>
                    <td>{{ $upgrade->employee ? $upgrade->employee->name . ' ' . $upgrade->employee->sername : 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Payment Status</th>
                    <td>{{ $upgrade->payment_status == 'pending' ? 'Pending' : 'Success' }}</td>
                </tr>
            </table>
        </div>
        
        <div class="info-section">
            <h4>Student Information</h4>
            <table>
                <tr>
                    <th style="width: 30%;">Student ID</th>
                    <td>{{ $upgrade->student->id }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $upgrade->student->name }} {{ $upgrade->student->sername }}</td>
                </tr>
                <tr>
                    <th>Major</th>
                    <td>{{ $upgrade->major->name }}</td>
                </tr>
            </table>
        </div>
        
        <h4>Subject Upgrades</h4>
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Credits</th>
                    <th>Credit Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($upgrade->upgradeDetails as $detail)
                <tr>
                    <td>{{ $detail->subject->name }}</td>
                    <td>{{ $detail->subject->credit->qty }}</td>
                    <td>{{ number_format($detail->subject->credit->price, 2) }}</td>
                    <td>{{ number_format($detail->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align: right">Total:</th>
                    <th>{{ number_format($totalAmount, 2) }}</th>
                </tr>
            </tfoot>
        </table>
        
     
        
     
    </div>
</body>
</html>
