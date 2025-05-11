<?php

namespace App\Http\Controllers;

use App\Models\Year;
use Illuminate\Http\Request;

class YearController extends Controller
{
    public function index()
    {
        $years = Year::all();
        return view('Dashboard.years.index', compact('years'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:10|unique:years,name',
        ]);

        Year::create($validatedData);

        return redirect()->route('years.index')
            ->with('sweet_alert', [
                'type' => 'success',
                'title' => 'ສຳເລັດ!',
                'text' => 'ສ້າງປີການສຶກສາສຳເລັດແລ້ວ.'
            ]);
    }

    public function update(Request $request, Year $year)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:10|unique:years,name,' . $year->id,
        ]);

        $year->update($validatedData);

        return redirect()->route('years.index')
            ->with('sweet_alert', [
                'type' => 'success',
                'title' => 'ສຳເລັດ!',
                'text' => 'ອັບເດດປີການສຶກສາສຳເລັດແລ້ວ.'
            ]);
    }

    public function destroy(Year $year)
    {
        try {
            // Delete the year (majors will be deleted, which will cascade delete related data)
            $year->delete();

            return redirect()->route('years.index')
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'ສຳເລັດ!',
                    'text' => 'ລຶບປີການສຶກສາແລະຂໍ້ມູນທີ່ກ່ຽວຂ້ອງທັງໝົດສຳເລັດແລ້ວ.'
                ]);
        } catch (\Exception $e) {
            return redirect()->route('years.index')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ຜິດພາດ!',
                    'text' => 'ລົ້ມເຫຼວໃນການລຶບປີການສຶກສາ: ' . $e->getMessage()
                ]);
        }
    }

    public function exportPDF()
    {
        $years = Year::with('majors')->get();
        
        $data = [
            'years' => $years,
            'date' => now()
        ];
        
        $pdf = \PDF::loadView('pdfs.years', $data);
        return $pdf->download('all-years.pdf');
    }

    public function getYears()
    {
        $years = \App\Models\Year::orderBy('name', 'desc')->get();
        return response()->json($years);
    }
}
