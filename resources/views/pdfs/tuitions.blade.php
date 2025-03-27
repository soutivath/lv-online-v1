<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>All Tuition Fees</title>
    <style>
        body {
            font-family: 'Phetsarath OT', sans-serif;
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
            <h1>ALL TUITION FEES REPORT</h1>
            <h3>Laovieng College</h3>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Price</th>
                    <th>Majors Using This Fee</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tuitions as $tuition)
                <tr>
                    <td>{{ $tuition->id }}</td>
                    <td>{{ number_format($tuition->price, 2) }}</td>
                    <td>{{ $tuition->majors->count() }}</td>
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
