<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Models\Semester;
use App\Models\Term;
use App\Models\Year;
use App\Models\Tuition;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function index()
    {
        $majors = Major::with(['semester', 'term', 'year', 'tuition'])->get();
        $semesters = Semester::all();
        $terms = Term::all();
        $years = Year::all();
        $tuitions = Tuition::all();
        
        return view('Dashboard.majors.index', compact('majors', 'semesters', 'terms', 'years', 'tuitions'));
    }

    /**
     * Store a newly created major in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:15',
            'semester_id' => 'required|exists:semesters,id',
            'term_id' => 'required|exists:terms,id',
            'year_id' => 'required|exists:years,id',
            'tuition_id' => 'required|exists:tuitions,id',
            'sokhn' => 'required|string|max:12',
        ]);

        Major::create($validatedData);

        return redirect()->route('majors.index')
            ->with('sweet_alert', [
                'type' => 'success',
                'title' => 'ສຳເລັດ!',
                'text' => 'ສ້າງສາຂາສຳເລັດແລ້ວ.'
            ]);
    }

    public function update(Request $request, Major $major)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:15',
            'semester_id' => 'required|exists:semesters,id',
            'term_id' => 'required|exists:terms,id',
            'year_id' => 'required|exists:years,id',
            'tuition_id' => 'required|exists:tuitions,id',
            'sokhn' => 'required|string|max:12',
        ]);

        $major->update($validatedData);

        return redirect()->route('majors.index')
            ->with('sweet_alert', [
                'type' => 'success',
                'title' => 'ສຳເລັດ!',
                'text' => 'ອັບເດດສາຂາສຳເລັດແລ້ວ.'
            ]);
    }

    public function destroy(Major $major)
    {
        try {
            // Delete the major (related registration_details, payments, and upgrades will be cascaded)
            $major->delete();

            return redirect()->route('majors.index')
                ->with('sweet_alert', [
                    'type' => 'success',
                    'title' => 'ສຳເລັດ!',
                    'text' => 'ລຶບສາຂາແລະຂໍ້ມູນທີ່ກ່ຽວຂ້ອງທັງໝົດສຳເລັດແລ້ວ.'
                ]);
        } catch (\Exception $e) {
            return redirect()->route('majors.index')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ຜິດພາດ!',
                    'text' => 'ລົ້ມເຫຼວໃນການລຶບສາຂາ: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Display the specified major.
     *
     * @param  \App\Models\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function show(Major $major)
    {
        // Load related data
        $major->load(['semester', 'term', 'year', 'tuition']);
        
        return view('Dashboard.majors.show', compact('major'));
    }

    /**
     * Show the form for creating a new major.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $semesters = \App\Models\Semester::all();
        $terms = \App\Models\Term::all();
        $years = \App\Models\Year::all();
        $tuitions = \App\Models\Tuition::all();
        
        return view('Dashboard.majors.create', compact('semesters', 'terms', 'years', 'tuitions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function edit(Major $major)
    {
        // Load relationships
        $major->load(['semester', 'term', 'year', 'tuition']);
        
        // If the request is AJAX, return JSON data for modal editing
        if (request()->ajax()) {
            return response()->json([
                'major' => $major
            ]);
        }

        // For non-AJAX requests, display the edit page
        $semesters = Semester::all();
        $terms = Term::all();
        $years = Year::all();
        $tuitions = Tuition::all();
        
        return view('Dashboard.majors.edit', compact('major', 'semesters', 'terms', 'years', 'tuitions'));
    }

    /**
     * Export a specific major to PDF.
     *
     * @param \App\Models\Major $major
     * @return \Illuminate\Http\Response
     */
    public function exportPDF(Major $major)
    {
        $major->load(['semester', 'term', 'year', 'tuition']);
        
        $data = [
            'major' => $major,
            'date' => now()
        ];
        
        $pdf = \PDF::loadView('pdfs.major', $data);
        $pdf->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'chroot' => public_path(),
        ]);
        
        return $pdf->download('major-'.$major->id.'.pdf');
    }

    /**
     * Export all majors to PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportAllPDF()
    {
        $majors = Major::with(['semester', 'term', 'year', 'tuition'])->get();
        
        $data = [
            'majors' => $majors,
            'title' => 'ALL MAJORS REPORT',
            'date' => now()
        ];
        
        // The controller now needs to point to the proper template
        $pdf = \PDF::loadView('Dashboard.majors.pdf.all-majors', $data);
        $pdf->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'chroot' => public_path(),
        ]);
        
        return $pdf->download('all-majors.pdf');
    }

    /**
     * Get majors filtered by year, term, and semester
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFilteredMajors(Request $request)
    {
        try {
            $query = Major::with(['semester', 'term', 'year', 'tuition']);
            
            if ($request->has('year_id') && $request->year_id) {
                $query->where('year_id', $request->year_id);
            }
            
            if ($request->has('term_id') && $request->term_id) {
                $query->where('term_id', $request->term_id);
            }
            
            if ($request->has('semester_id') && $request->semester_id) {
                $query->where('semester_id', $request->semester_id);
            }
            
            $majors = $query->get();
           
            return response()->json(['majors' => $majors]);
        } catch (\Exception $e) {
            // Return error with details for debugging
            return response()->json([
                'error' => true,
                'message' => 'Failed to filter majors: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getMajorsByYearTermSemester($yearId, $termId, $semesterId)
    {
        $majors = \App\Models\Major::where('year_id', $yearId)
            ->where('term_id', $termId)
            ->where('semester_id', $semesterId)
            ->orderBy('name')
            ->get();
        return response()->json($majors);
    }
}
