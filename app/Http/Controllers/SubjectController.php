<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Credit;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('credit')->get();
        $credits = Credit::all();
        return view('Dashboard.subjects.index', compact('subjects', 'credits'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:20',
            'credit_id' => 'required|exists:credits,id',
        ]);

        Subject::create($validatedData);

        return redirect()->route('subjects.index')
            ->with('sweet_alert', [
                'type' => 'success',
                'title' => 'Success!',
                'text' => 'Subject created successfully.'
            ]);
    }

    public function update(Request $request, Subject $subject)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:20',
            'credit_id' => 'required|exists:credits,id',
        ]);

        $subject->update($validatedData);

        return redirect()->route('subjects.index')
            ->with('sweet_alert', [
                'type' => 'success',
                'title' => 'Success!',
                'text' => 'Subject updated successfully.'
            ]);
    }

    public function destroy(Subject $subject)
    {
        try {
            // Delete the subject (related upgrade_details will be cascaded)
            $subject->delete();

            return redirect()->route('subjects.index')
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'Success!',
                    'text' => 'Subject and all related data deleted successfully.'
                ]);
        } catch (\Exception $e) {
            return redirect()->route('subjects.index')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'Error!',
                    'text' => 'Failed to delete subject: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Display the specified subject.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        // Load related data
        $subject->load(['credit']);
        
        return view('Dashboard.subjects.show', compact('subject'));
    }

    /**
     * Show the form for creating a new subject.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $credits = Credit::all();
        return view('Dashboard.subjects.create', compact('credits'));
    }

    /**
     * Export a specific subject to PDF.
     *
     * @param \App\Models\Subject $subject
     * @return \Illuminate\Http\Response
     */
    public function exportPDF(Subject $subject = null)
    {
        // If no subject is provided, export all subjects
        if (!$subject) {
            return $this->exportAllPDF();
        }

        $subject->load('credit');
        
        $data = [
            'subject' => $subject,
            'date' => now()
        ];
        
        $pdf = \PDF::loadView('pdfs.subject', $data);
        $pdf->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'chroot' => public_path(),
        ]);
        
        return $pdf->download('subject-'.$subject->id.'.pdf');
    }

    /**
     * Export all subjects to PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportAllPDF()
    {
        $subjects = Subject::with('credit')->get();
        
        $data = [
            'subjects' => $subjects,
            'title' => 'ALL SUBJECTS REPORT',
            'date' => now()
        ];
        
        $pdf = \PDF::loadView('pdfs.subjects', $data);
        $pdf->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'chroot' => public_path(),
        ]);
        
        return $pdf->download('all-subjects.pdf');
    }
}
