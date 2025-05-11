<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ນັກສຶກສາທັງໝົດ</title>
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
            <h1>ລາຍງານນັກສຶກສາທັງໝົດ<br/><span style="font-size: 0.8em;">ALL STUDENTS REPORT</span></h1>
            <h3>ວິທະຍາໄລລາວວຽງ<br/><span style="font-size: 0.8em;">Laovieng College</span></h3>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ລະຫັດ<br/><span style="font-size: 0.8em;">ID</span></th>
                    <th>ຊື່<br/><span style="font-size: 0.8em;">Name</span></th>
                    <th>ນາມສະກຸນ<br/><span style="font-size: 0.8em;">Surname</span></th>
                    <th>ເພດ<br/><span style="font-size: 0.8em;">Gender</span></th>
                    <th>ວັນເດືອນປີເກີດ<br/><span style="font-size: 0.8em;">Birthday</span></th>
                    <th>ສັນຊາດ<br/><span style="font-size: 0.8em;">Nationality</span></th>
                    <th>ເບີໂທ<br/><span style="font-size: 0.8em;">Phone</span></th>
                    <th>ອີເມວ<br/><span style="font-size: 0.8em;">Email</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td>{{ $student->id }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->sername }}</td>
                    <td>{{ $student->gender }}</td>
                    <td>{{ \Carbon\Carbon::parse($student->birthday)->format('d/m/Y') }}</td>
                    <td>{{ $student->nationality }}</td>
                    <td>{{ $student->tell }}</td>
                    <td>{{ $student->user ? $student->user->email : 'ບໍ່ມີບັນຊີ / No account' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>
</body>
</html>
