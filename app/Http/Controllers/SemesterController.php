<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::all();
        return view('Dashboard.semesters.index', compact('semesters'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:15|unique:semesters,name',
        ]);

        Semester::create($validatedData);

        return redirect()->route('semesters.index')
            ->with('sweet_alert', [
                'type' => 'success',
                'title' => 'ສຳເລັດ!',
                'text' => 'ສ້າງເທີມສຳເລັດແລ້ວ.'
            ]);
    }

    public function update(Request $request, Semester $semester)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:15|unique:semesters,name,' . $semester->id,
        ]);

        $semester->update($validatedData);

        return redirect()->route('semesters.index')
            ->with('sweet_alert', [
                'type' => 'success',
                'title' => 'ສຳເລັດ!',
                'text' => 'ອັບເດດເທີມສຳເລັດແລ້ວ.'
            ]);
    }

    public function destroy(Semester $semester)
    {
        try {
            // Delete the semester (majors will be deleted, which will cascade delete related data)
            $semester->delete();

            return redirect()->route('semesters.index')
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'ສຳເລັດ!',
                    'text' => 'ລຶບເທີມແລະຂໍ້ມູນທີ່ກ່ຽວຂ້ອງທັງໝົດສຳເລັດແລ້ວ.'
                ]);
        } catch (\Exception $e) {
            return redirect()->route('semesters.index')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ຜິດພາດ!',
                    'text' => 'ລົ້ມເຫຼວໃນການລຶບເທີມ: ' . $e->getMessage()
                ]);
        }
    }

    public function exportPDF()
    {
       
        $semesters = Semester::with('majors')->get();
        
        $data = [
            'semesters' => $semesters,
            'date' => now()
        ];
        
        $pdf = \PDF::loadView('pdfs.semesters', $data);
        return $pdf->download('all-semesters.pdf');
    }

    public function getSemestersByYearTerm($yearId, $termId)
    {
        $semesters = \App\Models\Semester::orderBy('name')->get();
        return response()->json($semesters);
    }
}
