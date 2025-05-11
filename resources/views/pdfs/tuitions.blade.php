<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ຄ່າຮຽນທັງໝົດ</title>
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
            <h1>ລາຍງານຄ່າຮຽນທັງໝົດ<br/><span style="font-size: 0.8em;">ALL TUITION FEES REPORT</span></h1>
            <h3>ວິທະຍາໄລລາວວຽງ<br/><span style="font-size: 0.8em;">Laovieng College</span></h3>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ລະຫັດ<br/><span style="font-size: 0.8em;">ID</span></th>
                    <th>ລາຄາ<br/><span style="font-size: 0.8em;">Price</span></th>
                    <th>ສາຂາວິຊາທີ່ໃຊ້ຄ່າຮຽນນີ້<br/><span style="font-size: 0.8em;">Majors Using This Fee</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($tuitions as $tuition)
                <tr>
                    <td>{{ $tuition->id }}</td>
                    <td>{{ number_format($tuition->price, 2) }}</td>
                    <td>{{ $tuition->majors->count() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="footer">
            <!-- Footer content removed -->
        </div>
    </div>
</body>
</html>
