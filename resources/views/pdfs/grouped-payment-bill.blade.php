<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ໃບເກັບເງິນ - {{ $bill_number }}</title>
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
        .bill-info {
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
        .qr-code {
            text-align: center;
            margin-top: 30px;
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
            <h1>ໃບບິນການຊຳລະເງິນ<br/><span style="font-size: 0.8em;">PAYMENT BILL</span></h1>
            <h3>ວິທະຍາໄລລາວວຽງ<br/><span style="font-size: 0.8em;">Laovieng College</span></h3>
        </div>
        
        <div class="bill-info">
            <p><strong>ເລກບິນ / Bill No:</strong> {{ $bill_number }}</p>
            <p><strong>ວັນທີ / Date:</strong> {{ \Carbon\Carbon::parse($date)->format('d/m/Y H:i') }}</p>
            <p><strong>ລະຫັດນັກສຶກສາ / Student ID:</strong> {{ $student->id }}</p>
            <p><strong>ຊື່ນັກສຶກສາ / Student Name:</strong> {{ $student->name }} {{ $student->sername }}</p>
            <p><strong>ຜູ້ຮັບເງິນ / Received By:</strong> {{ isset($employee) ? $employee->name . ' ' . $employee->sername : 'ບໍ່ມີ' }}</p>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ສາຂາວິຊາ<br/><span style="font-size: 0.8em;">Major</span></th>
                    <th>ພາກຮຽນ<br/><span style="font-size: 0.8em;">Semester</span></th>
                    <th>ເທີມ<br/><span style="font-size: 0.8em;">Term</span></th>
                    <th>ປີການສຶກສາ<br/><span style="font-size: 0.8em;">Year</span></th>
                    <th>ລາຄາພື້ນຖານ<br/><span style="font-size: 0.8em;">Base Price</span></th>
                    <th>ສ່ວນຫຼຸດ<br/><span style="font-size: 0.8em;">Discount</span></th>
                    <th>ລາຄາສຸດທ້າຍ<br/><span style="font-size: 0.8em;">Final Price</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->major->name }}</td>
                    <td>{{ $payment->major->semester->name }}</td>
                    <td>{{ $payment->major->term->name }}</td>
                    <td>{{ $payment->major->year->name }}</td>
                    <td>{{ number_format($payment->detail_price, 2) }}</td>
                    <td>{{ $payment->pro }}% ({{ number_format($payment->detail_price - $payment->total_price, 2) }})</td>
                    <td>{{ number_format($payment->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6" style="text-align: right;">ຈຳນວນເງິນທັງໝົດ / Total:</th>
                    <th>{{ number_format($total, 2) }}</th>
                </tr>
            </tfoot>
        </table>
        
        <div class="totals">
            <p><strong>ຈຳນວນເງິນທັງໝົດ / Total Amount: {{ number_format($total, 2) }}</strong></p>
        </div>
        
        {{-- <div class="qr-code">
            <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(200)->margin(1)->generate($bill_number)) }}" alt="Payment QR Code">
            <p>Scan to verify payment</p>
        </div> --}}
        
    </div>
</body>
</html>
