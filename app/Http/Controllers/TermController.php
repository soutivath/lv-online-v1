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
                'title' => 'ສຳເລັດ!',
                'text' => 'ສ້າງພາກຮຽນສຳເລັດແລ້ວ.'
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
                'title' => 'ສຳເລັດ!',
                'text' => 'ອັບເດດພາກຮຽນສຳເລັດແລ້ວ.'
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
                    'title' => 'ສຳເລັດ!',
                    'text' => 'ລຶບພາກຮຽນແລະຂໍ້ມູນທີ່ກ່ຽວຂ້ອງທັງໝົດສຳເລັດແລ້ວ.'
                ]);
        } catch (\Exception $e) {
            return redirect()->route('terms.index')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ຜິດພາດ!',
                    'text' => 'ລົ້ມເຫຼວໃນການລຶບພາກຮຽນ: ' . $e->getMessage()
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
