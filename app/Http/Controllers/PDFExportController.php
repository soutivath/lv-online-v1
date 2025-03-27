<?php

namespace App\Http\Controllers;

use PDF;
use QrCode;
use Illuminate\Http\Request;

class PDFExportController extends Controller
{
    protected function generatePDF($view, $data, $filename)
    {
        $pdf = PDF::loadView($view, $data);
        
        // Set PDF options to ensure images are loaded properly
        $pdf->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'chroot' => public_path(), // Add this to allow access to files in the public directory
        ]);
        
        return $pdf->download($filename);
    }

    // Add this helper method for student PDF exports
    public function exportStudentPDF($student)
    {
        // Prepare student data
        $data = [
            'student' => $student,
            // Add any other necessary data
        ];
        
        $pdf = PDF::loadView('pdfs.student', $data);
        $pdf->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'chroot' => public_path(), 
        ]);
        
        return $pdf->download('student-'.$student->id.'.pdf');
    }

    // Add this helper method for employee PDF exports
    public function exportEmployeePDF($employee)
    {
        // Prepare employee data
        $data = [
            'employee' => $employee,
            // Add any other necessary data
        ];
        
        $pdf = PDF::loadView('pdfs.employee', $data);
        $pdf->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'chroot' => public_path(),
        ]);
        
        return $pdf->download('employee-'.$employee->id.'.pdf');
    }
    
    protected function generatePaymentBill($payment)
    {
        // Generate QR code content (payment details)
        $qrContent = json_encode([
            'id' => $payment->id,
            'student_id' => $payment->student_id,
            'student_name' => $payment->student->name . ' ' . $payment->student->sername,
            'amount' => $payment->total_price,
            'date' => $payment->date,
            'major' => $payment->major->name,
        ]);
        
        // Generate QR code image
        $qrCode = base64_encode(QrCode::format('png')
                 ->size(200)
                 ->margin(1)
                 ->generate($qrContent));
        
        $data = [
            'payment' => $payment,
            'qrCode' => $qrCode
        ];
        
        $pdf = PDF::loadView('pdfs.payment-bill', $data);
        return $pdf->download('payment-bill-'.$payment->id.'.pdf');
    }
}
