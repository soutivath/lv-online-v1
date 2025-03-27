<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="https://fonts.googleapis.com/css2?family=Phetsarath:wght@400;700&display=swap" rel="stylesheet">

    <title>All Majors</title>
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
            <h1>ALL MAJORS REPORT</h1>
            <h3>Laovieng College</h3>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Semester</th>
                    <th>Term</th>
                    <th>Year</th>
                    <th>Tuition Fee</th>
                    <th>Sokhn</th>
                </tr>
            </thead>
            <tbody>
                @foreach($majors as $major)
                <tr>
                    <td>{{ $major->id }}</td>
                    <td>{{ $major->name }}</td>
                    <td>{{ $major->semester->name }}</td>
                    <td>{{ $major->term->name }}</td>
                    <td>{{ $major->year->name }}</td>
                    <td>{{ number_format($major->tuition->price, 2) }}</td>
                    <td>{{ $major->sokhn }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
