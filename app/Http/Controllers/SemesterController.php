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
                'title' => 'Success!',
                'text' => 'Semester created successfully.'
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
                'title' => 'Success!',
                'text' => 'Semester updated successfully.'
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
                    'title' => 'Success!',
                    'text' => 'Semester and all related data deleted successfully.'
                ]);
        } catch (\Exception $e) {
            return redirect()->route('semesters.index')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'text' => 'Failed to delete semester: ' . $e->getMessage()
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
}
