<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="https://fonts.googleapis.com/css2?family=Phetsarath:wght@400;700&display=swap" rel="stylesheet">
    <title>ວິຊາຮຽນທັງໝົດ</title>
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
            <h1>ລາຍງານວິຊາທັງໝົດ<br/><span style="font-size: 0.8em;">ALL SUBJECTS REPORT</span></h1>
            <h3>ວິທະຍາໄລລາວວຽງ<br/><span style="font-size: 0.8em;">Laovieng College</span></h3>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ລະຫັດ<br/><span style="font-size: 0.8em;">ID</span></th>
                    <th>ຊື່<br/><span style="font-size: 0.8em;">Name</span></th>
                    <th>ໜ່ວຍກິດ<br/><span style="font-size: 0.8em;">Credits</span></th>
                    <th>ລາຄາໜ່ວຍກິດ<br/><span style="font-size: 0.8em;">Credit Price</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($subjects as $subject)
                <tr>
                    <td>{{ $subject->id }}</td>
                    <td>{{ $subject->name }}</td>
                    <td>{{ $subject->credit->qty }}</td>
                    <td>{{ number_format($subject->credit->price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
