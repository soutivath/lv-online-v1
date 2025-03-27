<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    public function index()
    {
        $credits = Credit::all();
        return view('Dashboard.credits.index', compact('credits'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'price' => 'required|numeric|min:0',
            'qty' => 'required|string|max:5',
        ]);

        Credit::create($validatedData);

        return redirect()->route('credits.index')
            ->with('sweet_alert', [
                'type' => 'success',
                'title' => 'Success!',
                'text' => 'Credit created successfully.'
            ]);
    }

    public function update(Request $request, Credit $credit)
    {
        $validatedData = $request->validate([
            'price' => 'required|numeric|min:0',
            'qty' => 'required|string|max:5',
        ]);

        $credit->update($validatedData);

        return redirect()->route('credits.index')
            ->with('sweet_alert', [
                'type' => 'success',
                'title' => 'Success!',
                'text' => 'Credit updated successfully.'
            ]);
    }

    public function destroy(Credit $credit)
    {
        // Check if credit is being used by any subject
        if ($credit->subjects()->count() > 0) {
            return redirect()->route('credits.index')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'text' => 'Cannot delete this credit as it is being used by one or more subjects.'
                ]);
        }

        $credit->delete();

        return redirect()->route('credits.index')
            ->with('sweet_alert', [
                'type' => 'success',
                'title' => 'Success!',
                'text' => 'Credit deleted successfully.'
            ]);
    }

    public function exportPDF()
    {
        $credits = Credit::with('subjects')->get();
        
        $data = [
            'credits' => $credits,
            'date' => now()
        ];
        
        $pdf = \PDF::loadView('pdfs.credits', $data);
        return $pdf->download('all-credits.pdf');
    }
}
