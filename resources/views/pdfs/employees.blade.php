<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ພະນັກງານທັງໝົດ</title>
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
            <h1>ລາຍງານພະນັກງານທັງໝົດ<br/><span style="font-size: 0.8em;">ALL EMPLOYEES REPORT</span></h1>
            <h3>ວິທະຍາໄລລາວວຽງ<br/><span style="font-size: 0.8em;">Laovieng College</span></h3>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ລະຫັດ<br/><span style="font-size: 0.8em;">ID</span></th>
                    <th>ຊື່<br/><span style="font-size: 0.8em;">Name</span></th>
                    <th>ນາມສະກຸນ<br/><span style="font-size: 0.8em;">Surname</span></th>
                    <th>ເພດ<br/><span style="font-size: 0.8em;">Gender</span></th>
                    <th>ວັນທີເຂົ້າຮ່ວມ<br/><span style="font-size: 0.8em;">Join Date</span></th>
                    <th>ບົດບາດ<br/><span style="font-size: 0.8em;">Role</span></th>
                    <th>ເບີໂທ<br/><span style="font-size: 0.8em;">Phone</span></th>
                    <th>ອີເມວ<br/><span style="font-size: 0.8em;">Email</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                <tr>
                    <td>{{ $employee->id }}</td>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->sername }}</td>
                    <td>{{ $employee->gender }}</td>
                    <td>{{ \Carbon\Carbon::parse($employee->date)->format('d/m/Y') }}</td>
                    <td>{{ $employee->role == 'admin' ? 'ແອັດມິນ / Administrator' : 'ຢູເຊີ / Teacher' }}</td>
                    <td>{{ $employee->tell }}</td>
                    <td>{{ $employee->user ? $employee->user->email : 'ບໍ່ມີບັນຊີ / No account' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
