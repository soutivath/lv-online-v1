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
                'title' => 'Success!',
                'text' => 'Academic year created successfully.'
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
                'title' => 'Success!',
                'text' => 'Academic year updated successfully.'
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
                    'title' => 'Success!',
                    'text' => 'Academic year and all related data deleted successfully.'
                ]);
        } catch (\Exception $e) {
            return redirect()->route('years.index')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'text' => 'Failed to delete academic year: ' . $e->getMessage()
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
}
