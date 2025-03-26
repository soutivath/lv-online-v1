@extends('Admin.Layout')
@section('title', 'addnews')
@section('contents')
    <div class="container py-5">
        <form action="insert" method="POST" class="border border-2 p-5 border-primary rounded-5" style="background-color:#c3c5d3 ;">
            <h1 class="text-center text-primary pb-2" >ແບບຟອມລົງທະບຽນຮຽນ</h1>
            @csrf
            <div class="mb-3" style="font-size: 20px">
                <label>ອັບໂຫລດຮູບ:</label>
                <input type="file" name="photo" accept="image/*" required>
            </div>

            @error('title')
                <div class="py-3">
                    <span class="text-danger">{{$message}}</span>
                </div>
            @enderror

            <div class="mb-3">
                <label for="title" class="form-label" style="font-size: 20px">ຂໍ້ມູນລູກນັກສຶກສາ</label>
                <input type="text" class="form-control" name="title" placeholder="ຊື່ ແລະ ນາມສະກຸນ(ລາວ).">
            </div>

            @error('title')
                <div class="py-3">
                    <span class="text-danger">{{$message}}</span>
                </div>
            @enderror

            <div class="mb-3">
                <label for="title" class="form-label" style="font-size: 20px">ຂໍ້ມູນລູກນັກສຶກສາ</label>
                <input type="text" class="form-control" name="shorttext" placeholder="ຊື່ ແລະ ນາມສະກຸນ(ອັງກິດ).">
            </div>
            @error('title')
                <div class="py-3">
                    <span class="text-danger">{{$message}}</span>
                </div>
            @enderror

            <div class="mb-3" style="font-size: 20px">
                <label>ເພດ</label>
                <select id="gender">
                    <option value="">ເລືອກເພດ</option>
                    <option value="ຊາຍ">ຊາຍ</option>
                    <option value="ຍິງ">ຍິງ</option>
                </select>  
                <label>ວັນເດືອນປີເກີດ</label>
                <input type="date" id="dob" required>  
                <label>ອາຍຸ</label>
                <input type="text" id="age" required>
            </div>

            @error('title')
                <div class="py-3">
                    <span class="text-danger">{{$message}}</span>
                </div>
            @enderror

            <div class="mb-3" style="font-size: 20px">
                <label for="shorttext" class="form-label">ທີ່ຢູ່ປັດຈຸບັນ</label>
                <input type="text" class="form-control" name="shorttext" placeholder="ບ້ານ ເມືອງ ແຂວງ.">
            </div>
            
            @error('shorttext')
                <div class="py-3">
                    <span class="text-danger">{{$message}}</span>
                </div>
            @enderror

            <div class="mb-3" style="font-size: 20px">
                <label for="shorttext" class="form-label">ເບີຕິດຕໍ່</label>
                <input type="text" class="form-control" name="shorttext" placeholder="ເບີໂທລະສັບ.">
            </div>
            @error('shorttext')
                <div class="py-3">
                    <span class="text-danger">{{$message}}</span>
                </div>
            @enderror
            
            <div class="mb-3" style="font-size: 20px">
                <label>ສາຂາ</label>
                <select name="level">
                    <option value="name">ສາຂາຮຽນ</option>
                    <option value="it">ສາຂາໄອທີ</option>
                    <option value="accout">ສາຂາການເງິນ-ບັນຊີ</option>
                    <option value="english">ສາຂາພາສາອັງກິດທຸລະກິດ</option>
                    <option value="china">ສາຂາພາສາຈີນທຸລະກິດ</option>
                </select>    
                <label>ປີຮຽນ</label>
                <select name="level">
                    <option value="name">ປີຮຽນ</option>
                    <option value="one">ປີ1</option>
                    <option value="two">ປີ2</option>
                    <option value="three">ປີ3</option>
                </select>
                <label>ເທີມຮຽນ</label>
            <select name="level">
                <option value="name">ເທີມຮຽນ</option>
                <option value="one">ເທີມ1</option>
                <option value="two">ເທີມ2</option>
            </select>
            <label>ພາກຮຽນ</label>
            <select name="level">
                <option value="name">ພາກຮຽນ</option>
                <option value="mor">ພາກເຊົ້າ</option>
                <option value="aft">ພາກບ່າຍ</option>
                <option value="evn">ພາກຄໍ່າ</option>
            </select>
            </div>
            @error('contents')
                <div class="py-3">
                    <span class="text-danger">{{$message}}</span>
                </div>
            @enderror

            <div class="" >
                <a href="/payment" class="btn btn-lg btn-warning px-4 rounded-5" style="font-size: 25px">ຊໍາລະຄ່າເທີມ</a>
            </div>  
        </form>
    </div>
@endsection
