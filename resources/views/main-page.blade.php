{{-- ການເອີ້ນເຂົ້າມາໃຊ້ ແລະ ປ່ຽນຊື່ --}}
@extends('Admin.Layout')
@section('title', 'about')
@section('contents')
    <div class="container">
        {{-- Row1 --}}
        <div class="row">
            {{-- col1 --}}
            <div class="col-8 pt-5">
                {{-- header --}}
                <div class="row">
                    <div class="col-md-5 text-center">
                        <h1 class="text-primary py-2">ເປີດຮັບນັກສຶກສາໃໝ່</h1>
                    </div>
                </div>
            </div>
            {{-- col2 --}}
            <div class="container-fluid">
                <div class="col-md-12 mx-auto">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title" style="font-size: 30px">ສາຂາຮຽນ:</h5>
                            <ul class="list-group">
                                <div class="container mt-50">
                                    <div class="container-fluid d-flex flex-wrap justify-content-center">
                                        <!-- สສາຂາໄອທີ-->
                                        <div class="col-md-3 d-flex align-items-center border p-3">
                                            <i class="bi bi-laptop fs-2 text-primary me-2"></i>
                                            <h5 class="m-0" style="font-size: 25px">ສາຂາໄອທີ  <br> (Computer Business Technology)</h5>
                                        </div>
                                
                                        <!-- ສາຂາການເງິນ-ບັນຊີ -->
                                        <div class="col-md-3 d-flex align-items-center border p-3">
                                            <i class="bi bi-calculator fs-2 text-success me-2"></i>
                                            <h5 class="m-0" style="font-size: 25px">ສາຂາການເງິນ-ບັນຊີ <br> (Finance-Accounting)</h5>
                                        </div>
                                
                                        <!-- ສາຂາພາາອັງກິດທຸລະກິດ -->
                                        <div class="col-md-3 d-flex align-items-center border p-3">
                                            <i class="bi bi-globe-americas fs-2 text-danger me-2"></i>
                                            <h5 class="m-0" style="font-size: 25px">ສາຂາພາສາອັງກິດທຸລະກິດ <br> (Business English)</h5>
                                        </div>
                                
                                        <!-- ສາຂາພາສາຈີນທຸລະກິດ -->
                                        <div class="col-md-3 d-flex align-items-center border p-3">
                                            <i class="bi bi-translate fs-2 text-warning me-2"></i>
                                            <h5 class="m-0" style="font-size: 25px">ສາຂາພາສາຈີນທຸລະກິດ <br> (Business Chinese)</h5>
                                        </div>
                                    </div>
                                </div>
                                
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Row-2 --}}
        <footer class="text-center mt-4" style="font-size: 25px">
            <h3> <strong>💥ພິເສດ: ຟຣີ ຕຳລາຮຽນ</strong> </h3>
            <p>✅ ທ່ານສາມາດສະໝັກຮຽນຕັ້ງແຕ່ມື້ນີ້ເປັນຕົ້ນໄປ</p>
            <p> ໝາຍເຫດ: ມີຫໍພັກສຳຫຼັບນັກສຶກສາຕ່າງແຂວງ ເຮົາມີຫໍພັກໄວ້ຮອງຮັບພາຍໃນວິທະຍາໄລເລີຍ</p>
            <p>📞ຕິດຕໍ່ສອບຖາມ: <strong>Laovieng College</strong></p>
            <p>📍ສະຖານທີ່: ບ້ານຄຳຮຸ່ງ ຮ່ອມ 5 ເມືອງ ໄຊທານີ ນະຄອນຫຼວງວຽງຈັນ 
                📍Location: Khamhoung Village, alley 5, Xaythany district, Vientiane Capital        </p>
            <p>📞 030 5857834, 020 22459565 | ✉📧 laoviengcollege.edu@gmail.com</p>
        </footer>
    </div>
@endsection
