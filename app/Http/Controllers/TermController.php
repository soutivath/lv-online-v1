<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function index()
    {
        $terms = Term::all();
        return view('Dashboard.terms.index', compact('terms'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:15|unique:terms,name',
        ]);

        Term::create($validatedData);

        return redirect()->route('terms.index')
            ->with('sweet_alert', [
                'type' => 'success',
                'title' => 'Success!',
                'text' => 'Term created successfully.'
            ]);
    }

    public function update(Request $request, Term $term)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:15|unique:terms,name,' . $term->id,
        ]);

        $term->update($validatedData);

        return redirect()->route('terms.index')
            ->with('sweet_alert', [
                'type' => 'success',
                'title' => 'Success!',
                'text' => 'Term updated successfully.'
            ]);
    }

    public function destroy(Term $term)
    {
        try {
            // Delete the term (majors will be deleted, which will cascade delete related data)
            $term->delete();

            return redirect()->route('terms.index')
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'Success!',
                    'text' => 'Term and all related data deleted successfully.'
                ]);
        } catch (\Exception $e) {
            return redirect()->route('terms.index')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'text' => 'Failed to delete term: ' . $e->getMessage()
                ]);
        }
    }

    public function exportPDF()
    {
        $terms = Term::with('majors')->get();
        
        $data = [
            'terms' => $terms,
            'date' => now()
        ];
        
        $pdf = \PDF::loadView('pdfs.terms', $data);
        return $pdf->download('all-terms.pdf');
    }

    public function getTermsByYear($yearId)
    {
        $terms = \App\Models\Term::orderBy('name')->get();
        return response()->json($terms);
    }
}
