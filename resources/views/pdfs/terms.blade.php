<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>All Terms</title>
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
            <h1>ALL TERMS REPORT</h1>
            <h3>Laovieng College</h3>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Majors Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($terms as $term)
                <tr>
                    <td>{{ $term->id }}</td>
                    <td>{{ $term->name }}</td>
                    <td>{{ $term->majors->count() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
