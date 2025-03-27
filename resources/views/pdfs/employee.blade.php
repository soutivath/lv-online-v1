<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Employee Information</title>
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
        .employee-image {
            text-align: center;
            margin-bottom: 20px;
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
            <h1>EMPLOYEE INFORMATION</h1>
            <h3>Laovieng College</h3>
        </div>
        
        <div class="employee-image">
            @if($employee->picture)
                <img src="{{ public_path('storage/'.str_replace('public/', '', $employee->picture)) }}" alt="Employee" style="max-height: 150px;">
            @else
                <div style="border: 1px solid #ddd; padding: 20px; display: inline-block;">
                    <p>No picture available</p>
                </div>
            @endif
        </div>
        
        <div class="info-section">
            <h4>Personal Information</h4>
            <table>
                <tr>
                    <th style="width: 30%;">Employee ID</th>
                    <td>{{ $employee->id }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $employee->name }}</td>
                </tr>
                <tr>
                    <th>Surname</th>
                    <td>{{ $employee->sername }}</td>
                </tr>
                <tr>
                    <th>Gender</th>
                    <td>{{ $employee->gender }}</td>
                </tr>
                <tr>
                    <th>Birthday</th>
                    <td>{{ \Carbon\Carbon::parse($employee->birthday)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>Join Date</th>
                    <td>{{ \Carbon\Carbon::parse($employee->date)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>{{ $employee->tell }}</td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>{{ $employee->address }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $employee->user ? $employee->user->email : 'No account' }}</td>
                </tr>
            </table>
        </div>
        
    </div>
</body>
</html>
