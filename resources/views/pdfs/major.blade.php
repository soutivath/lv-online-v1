<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Major Information</title>
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
            <h1>MAJOR INFORMATION</h1>
            <h3>Laovieng College</h3>
        </div>
        
        <h4>Major Details</h4>
        <table>
            <tr>
                <th style="width: 30%;">ID</th>
                <td>{{ $major->id }}</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $major->name }}</td>
            </tr>
            <tr>
                <th>Semester</th>
                <td>{{ $major->semester->name }}</td>
            </tr>
            <tr>
                <th>Term</th>
                <td>{{ $major->term->name }}</td>
            </tr>
            <tr>
                <th>Year</th>
                <td>{{ $major->year->name }}</td>
            </tr>
            <tr>
                <th>Tuition Fee</th>
                <td>{{ number_format($major->tuition->price, 2) }}</td>
            </tr>
            <tr>
                <th>Code</th>
                <td>{{ $major->sokhn }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
