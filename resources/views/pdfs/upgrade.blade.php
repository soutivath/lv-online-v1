<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ໃບຮັບເງິນການອັບເກຣດ</title>
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
        .totals {
            text-align: right;
            margin: 20px 0;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
        }
        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #000;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ໃບຮັບເງິນການປັບປຸງຄະແນນ<br/><span style="font-size: 0.8em;">GRADE UPGRADE RECEIPT</span></h1>
            <h3>ວິທະຍາໄລລາວວຽງ<br/><span style="font-size: 0.8em;">Laovieng College</span></h3>
        </div>
        
        <div class="info-section">
            <h4>ຂໍ້ມູນການປັບປຸງຄະແນນ<br/><span style="font-size: 0.8em;">Upgrade Information</span></h4>
            <table>
                <tr>
                    <th style="width: 30%;">ລະຫັດການປັບປຸງ<br/><span style="font-size: 0.8em;">Upgrade ID</span></th>
                    <td>{{ $upgrade->id }}</td>
                </tr>
                <tr>
                    <th>ວັນທີ<br/><span style="font-size: 0.8em;">Date</span></th>
                    <td>{{ \Carbon\Carbon::parse($upgrade->date)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>ພະນັກງານ<br/><span style="font-size: 0.8em;">Employee</span></th>
                    <td>{{ $upgrade->employee ? $upgrade->employee->name . ' ' . $upgrade->employee->sername : 'ບໍ່ມີ' }}</td>
                </tr>
                <tr>
                    <th>ສະຖານະການຊຳລະເງິນ<br/><span style="font-size: 0.8em;">Payment Status</span></th>
                    <td>{{ $upgrade->payment_status == 'pending' ? 'ລໍຖ້າ / Pending' : 'ສຳເລັດ / Success' }}</td>
                </tr>
            </table>
        </div>
        
        <div class="info-section">
            <h4>ຂໍ້ມູນນັກສຶກສາ<br/><span style="font-size: 0.8em;">Student Information</span></h4>
            <table>
                <tr>
                    <th style="width: 30%;">ລະຫັດນັກສຶກສາ<br/><span style="font-size: 0.8em;">Student ID</span></th>
                    <td>{{ $upgrade->student->id }}</td>
                </tr>
                <tr>
                    <th>ຊື່<br/><span style="font-size: 0.8em;">Name</span></th>
                    <td>{{ $upgrade->student->name }} {{ $upgrade->student->sername }}</td>
                </tr>
                <tr>
                    <th>ສາຂາວິຊາ<br/><span style="font-size: 0.8em;">Major</span></th>
                    <td>{{ $upgrade->major->name }}</td>
                </tr>
            </table>
        </div>
        
        <h4>ການປັບປຸງຄະແນນວິຊາ<br/><span style="font-size: 0.8em;">Subject Upgrades</span></h4>
        <table>
            <thead>
                <tr>
                    <th>ວິຊາ<br/><span style="font-size: 0.8em;">Subject</span></th>
                    <th>ໜ່ວຍກິດ<br/><span style="font-size: 0.8em;">Credits</span></th>
                    <th>ລາຄາໜ່ວຍກິດ<br/><span style="font-size: 0.8em;">Credit Price</span></th>
                    <th>ຈຳນວນເງິນ<br/><span style="font-size: 0.8em;">Amount</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($upgrade->upgradeDetails as $detail)
                <tr>
                    <td>{{ $detail->subject->name }}</td>
                    <td>{{ $detail->subject->credit->qty }}</td>
                    <td>{{ number_format($detail->subject->credit->price, 2) }}</td>
                    <td>{{ number_format($detail->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align: right">ຈຳນວນເງິນທັງໝົດ / Total:</th>
                    <th>{{ number_format($totalAmount, 2) }}</th>
                </tr>
            </tfoot>
        </table>
        
     
        
     
    </div>
</body>
</html>
