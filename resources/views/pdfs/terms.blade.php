<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ເທີມທັງໝົດ</title>
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
            <h1>ລາຍງານເທີມທັງໝົດ<br/><span style="font-size: 0.8em;">ALL TERMS REPORT</span></h1>
            <h3>ວິທະຍາໄລລາວວຽງ<br/><span style="font-size: 0.8em;">Laovieng College</span></h3>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ລະຫັດ<br/><span style="font-size: 0.8em;">ID</span></th>
                    <th>ຊື່<br/><span style="font-size: 0.8em;">Name</span></th>
                    <th>ຈຳນວນສາຂາວິຊາ<br/><span style="font-size: 0.8em;">Majors Count</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($terms as $term)
                <tr>
                    <td>{{ $term->id }}</td>
                    <td>{{ $term->name }}</td>
                    <td>{{ $term->majors->count() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
