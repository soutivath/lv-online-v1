<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Subject Information</title>
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
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SUBJECT INFORMATION</h1>
            <h3>Laovieng College</h3>
        </div>
        
        <h4>Subject Details</h4>
        <table>
            <tr>
                <th style="width: 30%;">ID</th>
                <td>{{ $subject->id }}</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $subject->name }}</td>
            </tr>
            <tr>
                <th>Credits</th>
                <td>{{ $subject->credit->qty }}</td>
            </tr>
            <tr>
                <th>Credit Price</th>
                <td>{{ number_format($subject->credit->price, 2) }}</td>
            </tr>
            <tr>
                <th>Total Price</th>
                <td>{{ number_format($subject->credit->qty * $subject->credit->price, 2) }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
