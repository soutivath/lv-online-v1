<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ຂໍ້ມູນພະນັກງານ</title>
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
            <h1>ຂໍ້ມູນພະນັກງານ<br/><span style="font-size: 0.8em;">EMPLOYEE INFORMATION</span></h1>
            <h3>ວິທະຍາໄລລາວວຽງ<br/><span style="font-size: 0.8em;">Laovieng College</span></h3>
        </div>
        
        <div class="employee-image">
            @if($employee->picture)
                <img src="{{ public_path('storage/'.str_replace('public/', '', $employee->picture)) }}" alt="ພະນັກງານ" style="max-height: 150px;">
            @else
                <div style="border: 1px solid #ddd; padding: 20px; display: inline-block;">
                    <p>ບໍ່ມີຮູບພາບ<br/><span style="font-size: 0.8em;">ບໍ່ມີຮູບພາບ</span></p>
                </div>
            @endif
        </div>
        
        <div class="info-section">
            <h4>ຂໍ້ມູນສ່ວນຕົວ<br/><span style="font-size: 0.8em;">Personal Information</span></h4>
            <table>
                <tr>
                    <th style="width: 30%;">ລະຫັດພະນັກງານ<br/><span style="font-size: 0.8em;">Employee ID</span></th>
                    <td>{{ $employee->id }}</td>
                </tr>
                <tr>
                    <th>ຊື່<br/><span style="font-size: 0.8em;">Name</span></th>
                    <td>{{ $employee->name }}</td>
                </tr>
                <tr>
                    <th>ນາມສະກຸນ<br/><span style="font-size: 0.8em;">Surname</span></th>
                    <td>{{ $employee->sername }}</td>
                </tr>
                <tr>
                    <th>ເພດ<br/><span style="font-size: 0.8em;">Gender</span></th>
                    <td>{{ $employee->gender }}</td>
                </tr>
                <tr>
                    <th>ວັນເດືອນປີເກີດ<br/><span style="font-size: 0.8em;">Birthday</span></th>
                    <td>{{ \Carbon\Carbon::parse($employee->birthday)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>ວັນທີເຂົ້າຮ່ວມ<br/><span style="font-size: 0.8em;">Join Date</span></th>
                    <td>{{ \Carbon\Carbon::parse($employee->date)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>ບົດບາດ<br/><span style="font-size: 0.8em;">Role</span></th>
                    <td>{{ $employee->role == 'admin' ? 'ແອັດມິນ / Administrator' : 'ຢູເຊີ / User' }}</td>
                </tr>
                <tr>
                    <th>ເບີໂທ<br/><span style="font-size: 0.8em;">Phone</span></th>
                    <td>{{ $employee->tell }}</td>
                </tr>
                <tr>
                    <th>ທີ່ຢູ່<br/><span style="font-size: 0.8em;">Address</span></th>
                    <td>{{ $employee->address }}</td>
                </tr>
                <tr>
                    <th>ອີເມວ<br/><span style="font-size: 0.8em;">Email</span></th>
                    <td>{{ $employee->user ? $employee->user->email : 'ບໍ່ມີບັນຊີ / No account' }}</td>
                </tr>
            </table>
        </div>
        
    </div>
</body>
</html>
