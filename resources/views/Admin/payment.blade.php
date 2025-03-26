@extends('Admin.payment')
@section('title', 'about')
@section('contents')
<title>ຟອມອັບເກຣດ</title>
<style>
    body {
        font-family: 'Phetsarath OT', sans-serif;
        background-color: #f8f8f8;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .container {
        background: white;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        width: 400px;
    }
    input, select, button {
        width: 100%;
        padding: 8px;
        margin: 5px 0;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
    button {
        background-color: #d4a5c9;
        border: none;
        color: white;
        cursor: pointer;
    }
    button:hover {
        background-color: #c083b8;
    }
</style>
</head>
<body>

<div class="container">
<h3>ລົງທະບຽນອັບເກຣດ</h3>
<form id="upgradeForm">
    <label>ລະຫັດອັບເກຣດ</label>
    <input type="text" id="upgradeId" required>

    <label>ວັນທີອັບເກຣດ</label>
    <input type="date" id="upgradeDate" required>

    <label>ລະຫັດນັກສຶກສາ</label>
    <input type="text" id="memberId" required>

    <label>ສາຂາ</label>
    <select name="level">
        <option value="name">ສາຂາຮຽນ</option>
        <option value="it">ສາຂາໄອທີ</option>
        <option value="accout">ສາຂາການເງິນ-ບັນຊີ</option>
        <option value="english">ສາຂາພາສາອັງກິດທຸລະກິດ</option>
        <option value="china">ສາຂາພາສາຈີນທຸລະກິດ</option>
    </select>    

    <label>ວິຊາຮຽນ</label>
    <input type="text" id="subject" required>

    <label>ຈ່າຍເງິນເປັນປີ</label>
    <select id="paymentYear" required>
        <option value="2024">2024</option>
        <option value="2025">2025</option>
        <option value="2026">2026</option>
    </select>

    <button type="button" onclick="submitForm()">ບັນທຶກ</button>
</form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
function submitForm() {
    let upgradeId = document.getElementById("upgradeId").value;
    let upgradeDate = document.getElementById("upgradeDate").value;
    let memberId = document.getElementById("memberId").value;
    let employeeId = document.getElementById("employeeId").value;
    let feeType = document.getElementById("feeType").value;
    let paymentYear = document.getElementById("paymentYear").value;

    if (paymentId && paymentDate && memberId && employeeId && feeType && paymentYear) {
        alert("ບັນທຶກຂໍ້ມູນສຳເລັດ!");
    } else {
        alert("ກະລຸນາປ້ອນຂໍ້ມູນໃຫ້ຄົບ");
    }
}
</script>
@endsection