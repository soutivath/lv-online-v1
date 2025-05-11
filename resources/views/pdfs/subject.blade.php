<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ຂໍ້ມູນວິຊາຮຽນ</title>
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
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ຂໍ້ມູນວິຊາ<br/><span style="font-size: 0.8em;">SUBJECT INFORMATION</span></h1>
            <h3>ວິທະຍາໄລລາວວຽງ<br/><span style="font-size: 0.8em;">Laovieng College</span></h3>
        </div>
        
        <h4>ລາຍລະອຽດວິຊາ<br/><span style="font-size: 0.8em;">Subject Details</span></h4>
        <table>
            <tr>
                <th style="width: 30%;">ລະຫັດ<br/><span style="font-size: 0.8em;">ID</span></th>
                <td>{{ $subject->id }}</td>
            </tr>
            <tr>
                <th>ຊື່<br/><span style="font-size: 0.8em;">Name</span></th>
                <td>{{ $subject->name }}</td>
            </tr>
            <tr>
                <th>ໜ່ວຍກິດ<br/><span style="font-size: 0.8em;">Credits</span></th>
                <td>{{ $subject->credit->qty }}</td>
            </tr>
            <tr>
                <th>ລາຄາໜ່ວຍກິດ<br/><span style="font-size: 0.8em;">Credit Price</span></th>
                <td>{{ number_format($subject->credit->price, 2) }}</td>
            </tr>
            <tr>
                <th>ລາຄາທັງໝົດ<br/><span style="font-size: 0.8em;">Total Price</span></th>
                <td>{{ number_format($subject->credit->qty * $subject->credit->price, 2) }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
