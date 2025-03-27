<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>All Upgrades</title>
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
            <h1>ALL UPGRADES REPORT</h1>
            <h3>Laovieng College</h3>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Student</th>
                    <th>Major</th>
                    <th>Subjects Count</th>
                    <th>Total Amount</th>
                    <th>Employee</th>
                </tr>
            </thead>
            <tbody>
                @foreach($upgrades as $upgrade)
                <tr>
                    <td>{{ $upgrade->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($upgrade->date)->format('d/m/Y') }}</td>
                    <td>{{ $upgrade->student->name }} {{ $upgrade->student->sername }}</td>
                    <td>{{ $upgrade->major->name }}</td>
                    <td>{{ $upgrade->upgradeDetails->count() }}</td>
                    <td>{{ number_format($upgrade->upgradeDetails->sum('total_price'), 2) }}</td>
                    <td>{{ $upgrade->employee ? $upgrade->employee->name : 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="footer">
            <!-- Footer content removed -->
        </div>
    </div>
</body>
</html>
