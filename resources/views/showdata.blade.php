@extends('Admin.Layout')
@section('title', 'showdata')
@section('contents')
    <div class="container py-4">
        {{-- table --}}
        <h1 class="text-primary py-4 text-center">ຕາຕະລາງສະແດງຂໍ້ມູນ</h1>
        
        <div class="d-flex flex-row-reverse mb-4">
            <a href='/addnews' class="btn btn-lg btn-primary px-5">ເພີ່ມຂໍ້ມູນໃໝ່</a>
        </div>
        <table class="table text-center table-warning table-bordered">
            <thead>
                <tr>
                    <th scope="col">ລະຫັດລູກຄ້າ</th>
                    <th scope="col">ຂໍ້ມູນລູກຄ້າ</th>
                    <th scope="col">ຊ່ອງທາງການຕິດຕໍ່</th>
                    <th scope="col">ຄວາມຄິດເຫັນ</th>
                    <th colspan="2">ຈັດການຂໍ້ມູນລູກຄ້າ</th>

                </tr>
            </thead>{{-- ສ່ວນຫົວຕາຕະລາງ --}}
            <tbody>
                @foreach ($newshow as $item)
                    
                
                <tr>
                    
                    <td> {{Str::Limit($item->title, 20)}} </td>
                    <td> {{Str::Limit($item->shorttext, 30)}} </td>
                    <td> {{Str::Limit($item->contents, 40)}} </td>
                    <td>
                        @if ($item->status == false)
                        <a href="#" class="btn btn-lg text-success">ເຜີຍແຜ່ແລ້ວ</a>
                        @else
                        <a href="#" class="btn btn-lg text-danger">ສະບັບຮ່າງ</a>
                        @endif
                    </td>
                    <td>
                        <i class="bi bi-pencil-square text-primary fs-4"></i>
                    </td>
                    <td>
                        <a href="{{ Route('delete', $item->id) }}">

                        <i class="bi bi-trash3-fill text-danger fs-4"></i>

                        </a>
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>
@endsection
