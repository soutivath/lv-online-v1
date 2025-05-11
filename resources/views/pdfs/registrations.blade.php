<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ການລົງທະບຽນທັງໝົດ</title>
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
            <h1>ລາຍງານການລົງທະບຽນທັງໝົດ<br/><span style="font-size: 0.8em;">ALL REGISTRATIONS REPORT</span></h1>
            <h3>ວິທະຍາໄລລາວວຽງ<br/><span style="font-size: 0.8em;">Laovieng College</span></h3>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ລະຫັດ<br/><span style="font-size: 0.8em;">ID</span></th>
                    <th>ວັນທີ<br/><span style="font-size: 0.8em;">Date</span></th>
                    <th>ນັກສຶກສາ<br/><span style="font-size: 0.8em;">Student</span></th>
                    <th>ສາຂາວິຊາ<br/><span style="font-size: 0.8em;">Major</span></th>
                    <th>ສ່ວນຫຼຸດ %<br/><span style="font-size: 0.8em;">Discount %</span></th>
                    <th>ລາຄາທັງໝົດ<br/><span style="font-size: 0.8em;">Total Price</span></th>
                    <th>ພະນັກງານ<br/><span style="font-size: 0.8em;">Employee</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $registration)
                <tr>
                    <td>{{ $registration->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($registration->date)->format('d/m/Y') }}</td>
                    <td>{{ $registration->student->name }} {{ $registration->student->sername }}</td>
                    <td>
                        @if($registration->registrationDetails->count() > 0)
                            {{ $registration->registrationDetails->first()->major->name }}
                        @else
                            ບໍ່ມີ
                        @endif
                    </td>
                    <td>{{ $registration->pro }}%</td>
                    <td>
                        @if($registration->registrationDetails->count() > 0)
                            {{ number_format($registration->registrationDetails->sum('total_price'), 2) }}
                        @else
                            0.00
                        @endif
                    </td>
                    <td>{{ $registration->employee ? $registration->employee->name : 'ບໍ່ມີ' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>
</body>
</html>
