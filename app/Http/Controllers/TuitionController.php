<?php

namespace App\Http\Controllers;

use App\Models\Tuition;
use Illuminate\Http\Request;

class TuitionController extends Controller
{
    public function index()
    {
        $tuitions = Tuition::all();
        return view('Dashboard.tuitions.index', compact('tuitions'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        Tuition::create($validatedData);

        return redirect()->route('tuitions.index')
            ->with('sweet_alert', [
                'type' => 'success',
                'title' => 'ສຳເລັດ!',
                'text' => 'ສ້າງຄ່າຮຽນສຳເລັດແລ້ວ.'
            ]);
    }

    public function update(Request $request, Tuition $tuition)
    {
        $validatedData = $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $tuition->update($validatedData);

        return redirect()->route('tuitions.index')
            ->with('sweet_alert', [
                'type' => 'success',
                'title' => 'ສຳເລັດ!',
                'text' => 'ອັບເດດຄ່າຮຽນສຳເລັດແລ້ວ.'
            ]);
    }

    public function destroy(Tuition $tuition)
    {
        // Check if tuition is being used by any major
        if ($tuition->majors()->count() > 0) {
            return redirect()->route('tuitions.index')
                ->with('sweet_alert', [
                    'type' => 'error',
                    'title' => 'ຜິດພາດ!',
                    'text' => 'ບໍ່ສາມາດລຶບຄ່າຮຽນນີ້ໄດ້ ເນື່ອງຈາກມັນກຳລັງຖືກໃຊ້ໂດຍໜຶ່ງຫຼືຫຼາຍສາຂາ.'
                ]);
        }

        $tuition->delete();

        return redirect()->route('tuitions.index')
            ->with('sweet_alert', [
                'type' => 'success',
                'title' => 'ສຳເລັດ!',
                'text' => 'ລຶບຄ່າຮຽນສຳເລັດແລ້ວ.'
            ]);
    }

    public function exportPDF()
    {
        $tuitions = Tuition::with('majors')->get();
        
        $data = [
            'tuitions' => $tuitions,
            'date' => now()
        ];
        
        $pdf = \PDF::loadView('pdfs.tuitions', $data);
        return $pdf->download('all-tuitions.pdf');
    }
}
