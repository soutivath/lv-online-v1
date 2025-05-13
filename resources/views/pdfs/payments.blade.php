<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ການຊຳລະເງິນທັງໝົດ</title>
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
        .totals {
            text-align: right;
            margin: 20px 0;
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
            <h1>ລາຍງານການຊຳລະເງິນທັງໝົດ<br/><span style="font-size: 0.8em;">ALL PAYMENTS REPORT</span></h1>
            <h3>ວິທະຍາໄລລາວວຽງ<br/><span style="font-size: 0.8em;">Laovieng College</span></h3>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ລະຫັດ<br/><span style="font-size: 0.8em;">ID</span></th>
                    <th>ວັນທີ<br/><span style="font-size: 0.8em;">Date</span></th>
                    <th>ນັກສຶກສາ<br/><span style="font-size: 0.8em;">Student</span></th>
                    <th>ສາຂາວິຊາ<br/><span style="font-size: 0.8em;">Major</span></th>
                    <th>ປີການສຶກສາ<br/><span style="font-size: 0.8em;">Year</span></th>
                    <th>ເທີມ<br/><span style="font-size: 0.8em;">Term</span></th>
                    <th>ພາກຮຽນ<br/><span style="font-size: 0.8em;">Semester</span></th>
                    <th>ຈຳນວນເງິນພື້ນຖານ<br/><span style="font-size: 0.8em;">Base Amount</span></th>
                    <th>ສ່ວນຫຼຸດ %<br/><span style="font-size: 0.8em;">Discount %</span></th>
                    <th>ຈຳນວນເງິນສຸດທ້າຍ<br/><span style="font-size: 0.8em;">Final Amount</span></th>
                    <th>ພະນັກງານ<br/><span style="font-size: 0.8em;">Employee</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($payment->date)->format('d/m/Y') }}</td>
                    <td>{{ $payment->student->name }} {{ $payment->student->sername }}</td>
                    <td>{{ $payment->major->name }}</td>
                    <td>{{ $payment->major->year->name ?? 'ບໍ່ມີ' }}</td>
                    <td>{{ $payment->major->term->name ?? 'ບໍ່ມີ' }}</td>
                    <td>{{ $payment->major->semester->name ?? 'ບໍ່ມີ' }}</td>
                    <td>{{ number_format($payment->detail_price, 2) }}</td>
                    <td>{{ $payment->pro }}%</td>
                    <td>{{ number_format($payment->total_price, 2) }}</td>
                    <td>{{ $payment->employee ? $payment->employee->name : 'ບໍ່ມີ' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="9" style="text-align: right">ຈຳນວນເງິນທັງໝົດ / Total:</th>
                    <th>{{ number_format($total, 2) }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
