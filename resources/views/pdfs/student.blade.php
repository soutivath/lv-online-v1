<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Student Information</title>
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
        .student-image {
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
        h4 {
            margin-top: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>STUDENT INFORMATION</h1>
            <h3>Laovieng College</h3>
        </div>
        
        <div class="student-image">
            @if($student->picture)
                <img src="{{ public_path('storage/'.str_replace('public/', '', $student->picture)) }}" alt="Student" style="max-height: 150px;">
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
                    <th style="width: 30%;">Student ID</th>
                    <td>{{ $student->id }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $student->name }}</td>
                </tr>
                <tr>
                    <th>Surname</th>
                    <td>{{ $student->sername }}</td>
                </tr>
                <tr>
                    <th>Gender</th>
                    <td>{{ $student->gender }}</td>
                </tr>
                <tr>
                    <th>Birthday</th>
                    <td>{{ \Carbon\Carbon::parse($student->birthday)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>Nationality</th>
                    <td>{{ $student->nationality }}</td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>{{ $student->tell }}</td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>{{ $student->address }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $student->user ? $student->user->email : 'No account' }}</td>
                </tr>
            </table>
        </div>
        
        @if($student->registrations->count() > 0)
        <div class="info-section">
            <h4>Registrations</h4>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Major</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($student->registrations as $registration)
                    <tr>
                        <td>{{ $registration->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($registration->date)->format('d/m/Y') }}</td>
                        <td>
                            @if($registration->registrationDetails->count() > 0)
                                {{ $registration->registrationDetails->first()->major->name }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if($registration->registrationDetails->count() > 0)
                                {{ number_format($registration->registrationDetails->sum('total_price'), 2) }}
                            @else
                                0.00
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        @if($student->payments->count() > 0)
        <div class="info-section">
            <h4>Payments</h4>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Major</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($student->payments as $payment)
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($payment->date)->format('d/m/Y') }}</td>
                        <td>{{ $payment->major->name }}</td>
                        <td>{{ number_format($payment->total_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Score Document Section -->
        @if($student->score)
            <div class="info-section">
                <h4>Score Document</h4>
                <div class="text-center">
                    <img src="{{ public_path('storage/'.str_replace('public/', '', $student->score)) }}" alt="Score Document" style="max-height: 300px;">
                </div>
            </div>
        @endif
        
    </div>
</body>
</html>
