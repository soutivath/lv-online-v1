<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Restaurant extends Controller
{
    public function addnews(){
        return view('addnews');
    }

    public function showdata(){
        $newshow=DB::table('Restaurant')->get();
        return view('showdata', compact('newshow'));
    }

    public function about(){
        return view('about');
    }

    public function insert(REQUEST $request){
        $request->validate(
            [
                'title'=>'required|max:50',
                'shorttext'=>'required',
                'contents'=>'required'
            ],
            [
                'title.required'=>'ກະລຸນາປ້ອນຫົວຂໍ້ຂ່າວຂອງທ່ານ',
                'title.max'=>'ຈຳນວນຕົວອັກສອນຫົວຂໍ້ຂ່າວບໍ່ຄວນຍາວເກີນ 50 ຕົວອັກສອນ',
                'shorttext.required'=>'ກະລຸນາປ້ອນເນື້ອໃນຫຍໍ້ຂ່າວຂອງທ່ານ',
                'contents.required'=>'ກະລຸນາປ້ອນເນື້ອໃນລວມຂ່າວຂອງທ່ານ'
            ],
        );

        $data=[
            'title'=>$request->title,
            'shorttext'=>$request->shorttext,
            'contents'=>$request->contents
        ];

        DB::table('Restaurant')->insert($data);
        return redirect('showdata');
    }

    function delete($id) {
        DB::table('Restaurant')->where('id',$id)->delete();
        return redirect('showdata');
    }
}
