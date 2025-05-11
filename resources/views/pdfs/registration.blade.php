<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ໃບຮັບເງິນການລົງທະບຽນ</title>
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
            <h1>ໃບຮັບເງິນການລົງທະບຽນ<br/><span style="font-size: 0.8em;">REGISTRATION RECEIPT</span></h1>
            <h3>ວິທະຍາໄລລາວວຽງ<br/><span style="font-size: 0.8em;">Laovieng College</span></h3>
        </div>
        
        <div class="info-section">
            <h4>ຂໍ້ມູນການລົງທະບຽນ<br/><span style="font-size: 0.8em;">Registration Information</span></h4>
            <table>
                <tr>
                    <th style="width: 30%;">ລະຫັດການລົງທະບຽນ<br/><span style="font-size: 0.8em;">Registration ID</span></th>
                    <td>{{ $registration->id }}</td>
                </tr>
                <tr>
                    <th>ວັນທີ<br/><span style="font-size: 0.8em;">Date</span></th>
                    <td>{{ \Carbon\Carbon::parse($registration->date)->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <th>ພະນັກງານ<br/><span style="font-size: 0.8em;">Employee</span></th>
                    <td>{{ $registration->employee ? $registration->employee->name . ' ' . $registration->employee->sername : 'ບໍ່ມີ' }}</td>
                </tr>
                <tr>
                    <th>ສ່ວນຫຼຸດ<br/><span style="font-size: 0.8em;">Discount</span></th>
                    <td>{{ $registration->pro }}%</td>
                </tr>
                <tr>
                    <th>ສະຖານະການຊຳລະເງິນ<br/><span style="font-size: 0.8em;">Payment Status</span></th>
                    <td>{{ $registration->payment_status == 'pending' ? 'ລໍຖ້າ / Pending' : 'ສຳເລັດ / Success' }}</td>
                </tr>
            </table>
        </div>
        
        <div class="info-section">
            <h4>ຂໍ້ມູນນັກສຶກສາ<br/><span style="font-size: 0.8em;">Student Information</span></h4>
            <table>
                <tr>
                    <th style="width: 30%;">ລະຫັດນັກສຶກສາ<br/><span style="font-size: 0.8em;">Student ID</span></th>
                    <td>{{ $registration->student->id }}</td>
                </tr>
                <tr>
                    <th>ຊື່<br/><span style="font-size: 0.8em;">Name</span></th>
                    <td>{{ $registration->student->name }} {{ $registration->student->sername }}</td>
                </tr>
                <tr>
                    <th>ເພດ<br/><span style="font-size: 0.8em;">Gender</span></th>
                    <td>{{ $registration->student->gender }}</td>
                </tr>
                <tr>
                    <th>ເບີໂທ<br/><span style="font-size: 0.8em;">Phone</span></th>
                    <td>{{ $registration->student->tell }}</td>
                </tr>
            </table>
        </div>
        
        <h4>ລາຍລະອຽດການລົງທະບຽນ<br/><span style="font-size: 0.8em;">Registration Details</span></h4>
        <table>
            <thead>
                <tr>
                    <th>ສາຂາວິຊາ<br/><span style="font-size: 0.8em;">Major</span></th>
                    <th>ພາກຮຽນ<br/><span style="font-size: 0.8em;">Semester</span></th>
                    <th>ເທີມ<br/><span style="font-size: 0.8em;">Term</span></th>
                    <th>ປີການສຶກສາ<br/><span style="font-size: 0.8em;">Year</span></th>
                    <th>ລາຄາພື້ນຖານ<br/><span style="font-size: 0.8em;">Base Price</span></th>
                    <th>ລາຄາສຸດທ້າຍ<br/><span style="font-size: 0.8em;">Final Price</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($registration->registrationDetails as $detail)
                <tr>
                    <td>{{ $detail->major->name }}</td>
                    <td>{{ $detail->major->semester->name }}</td>
                    <td>{{ $detail->major->term->name }}</td>
                    <td>{{ $detail->major->year->name }}</td>
                    <td>{{ number_format($detail->detail_price, 2) }}</td>
                    <td>{{ number_format($detail->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" style="text-align: right">ຈຳນວນເງິນທັງໝົດ / Total:</th>
                    <th>{{ number_format($registration->registrationDetails->sum('total_price'), 2) }}</th>
                </tr>
            </tfoot>
        </table>
        
        
        
       
    </div>
</body>
</html>
