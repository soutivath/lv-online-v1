<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ຂໍ້ມູນນັກສຶກສາ</title>
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
            <h1>ຂໍ້ມູນນັກສຶກສາ<br/><span style="font-size: 0.8em;">STUDENT INFORMATION</span></h1>
            <h3>ວິທະຍາໄລລາວວຽງ<br/><span style="font-size: 0.8em;">Laovieng College</span></h3>
        </div>
        
        <div class="student-image">
            @if($student->picture)
                <img src="{{ public_path('storage/'.str_replace('public/', '', $student->picture)) }}" alt="Student" style="max-height: 150px;">
            @else
                <div style="border: 1px solid #ddd; padding: 20px; display: inline-block;">
                    <p>ບໍ່ມີຮູບພາບ<br/><span style="font-size: 0.8em;">No picture available</span></p>
                </div>
            @endif
        </div>
        
        <div class="info-section">
            <h4>ຂໍ້ມູນສ່ວນຕົວ<br/><span style="font-size: 0.8em;">Personal Information</span></h4>
            <table>
                <tr>
                    <th style="width: 30%;">ລະຫັດນັກສຶກສາ<br/><span style="font-size: 0.8em;">Student ID</span></th>
                    <td>{{ $student->id }}</td>
                </tr>
                <tr>
                    <th>ຊື່<br/><span style="font-size: 0.8em;">Name</span></th>
                    <td>{{ $student->name }}</td>
                </tr>
                <tr>
                    <th>ນາມສະກຸນ<br/><span style="font-size: 0.8em;">Surname</span></th>
                    <td>{{ $student->sername }}</td>
                </tr>
                <tr>
                    <th>ເພດ<br/><span style="font-size: 0.8em;">Gender</span></th>
                    <td>{{ $student->gender }}</td>
                </tr>
                <tr>
                    <th>ວັນເດືອນປີເກີດ<br/><span style="font-size: 0.8em;">Birthday</span></th>
                    <td>{{ \Carbon\Carbon::parse($student->birthday)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>ສັນຊາດ<br/><span style="font-size: 0.8em;">Nationality</span></th>
                    <td>{{ $student->nationality }}</td>
                </tr>
                <tr>
                    <th>ເບີໂທ<br/><span style="font-size: 0.8em;">Phone</span></th>
                    <td>{{ $student->tell }}</td>
                </tr>
                <tr>
                    <th>ທີ່ຢູ່<br/><span style="font-size: 0.8em;">Address</span></th>
                    <td>{{ $student->address }}</td>
                </tr>
                <tr>
                    <th>ອີເມວ<br/><span style="font-size: 0.8em;">Email</span></th>
                    <td>{{ $student->user ? $student->user->email : 'ບໍ່ມີບັນຊີ / No account' }}</td>
                </tr>
            </table>
        </div>
        
        @if($student->registrations->count() > 0)
        <div class="info-section">
            <h4>ການລົງທະບຽນ<br/><span style="font-size: 0.8em;">Registrations</span></h4>
            <table>
                <thead>
                    <tr>
                        <th>ລະຫັດ<br/><span style="font-size: 0.8em;">ID</span></th>
                        <th>ວັນທີ<br/><span style="font-size: 0.8em;">Date</span></th>
                        <th>ສາຂາວິຊາ<br/><span style="font-size: 0.8em;">Major</span></th>
                        <th>ຈຳນວນເງິນ<br/><span style="font-size: 0.8em;">Amount</span></th>
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
                                ບໍ່ມີ
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
            <h4>ການຊຳລະເງິນ<br/><span style="font-size: 0.8em;">Payments</span></h4>
            <table>
                <thead>
                    <tr>
                        <th>ລະຫັດ<br/><span style="font-size: 0.8em;">ID</span></th>
                        <th>ວັນທີ<br/><span style="font-size: 0.8em;">Date</span></th>
                        <th>ສາຂາວິຊາ<br/><span style="font-size: 0.8em;">Major</span></th>
                        <th>ຈຳນວນເງິນ<br/><span style="font-size: 0.8em;">Amount</span></th>
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
                <h4>ເອກະສານຄະແນນ<br/><span style="font-size: 0.8em;">Score Document</span></h4>
                <div class="text-center">
                    <img src="{{ public_path('storage/'.str_replace('public/', '', $student->score)) }}" alt="Score Document" style="max-height: 300px;">
                </div>
            </div>
        @endif
        
    </div>
</body>
</html>
