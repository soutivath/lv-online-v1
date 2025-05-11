<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ຂໍ້ມູນສາຂາວິຊາ</title>
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
            <h1>ຂໍ້ມູນສາຂາວິຊາ<br/><span style="font-size: 0.8em;">MAJOR INFORMATION</span></h1>
            <h3>ວິທະຍາໄລລາວວຽງ<br/><span style="font-size: 0.8em;">Laovieng College</span></h3>
        </div>
        
        <h4>ລາຍລະອຽດສາຂາວິຊາ<br/><span style="font-size: 0.8em;">Major Details</span></h4>
        <table>
            <tr>
                <th style="width: 30%;">ລະຫັດ<br/><span style="font-size: 0.8em;">ID</span></th>
                <td>{{ $major->id }}</td>
            </tr>
            <tr>
                <th>ຊື່<br/><span style="font-size: 0.8em;">Name</span></th>
                <td>{{ $major->name }}</td>
            </tr>
            <tr>
                <th>ພາກຮຽນ<br/><span style="font-size: 0.8em;">Semester</span></th>
                <td>{{ $major->semester->name }}</td>
            </tr>
            <tr>
                <th>ເທີມ<br/><span style="font-size: 0.8em;">Term</span></th>
                <td>{{ $major->term->name }}</td>
            </tr>
            <tr>
                <th>ປີການສຶກສາ<br/><span style="font-size: 0.8em;">Year</span></th>
                <td>{{ $major->year->name }}</td>
            </tr>
            <tr>
                <th>ຄ່າຮຽນ<br/><span style="font-size: 0.8em;">Tuition Fee</span></th>
                <td>{{ number_format($major->tuition->price, 2) }}</td>
            </tr>
            <tr>
                <th>ລະຫັດ<br/><span style="font-size: 0.8em;">Code</span></th>
                <td>{{ $major->sokhn }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
