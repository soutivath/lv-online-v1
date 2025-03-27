<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Phetsarath:wght@400;700&display=swap" rel="stylesheet">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Phetsarath', sans-serif;
            font-size: 12pt;
            line-height: 1.6;
            margin: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
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
            margin-top: 20px;
            font-size: 10pt;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Semester</th>
                <th>Term</th>
                <th>Year</th>
                <th>Tuition Fee</th>
                <th>Code (Sokhn)</th>
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
  
</body>
</html>
