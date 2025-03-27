<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $table = 'registrations';
    protected $primaryKey = 'id';

    protected $fillable = [
        'student_id',
        'employee_id',
        'date',
        'pro',
        'payment_status',
        'payment_proof'
    ];

    protected $casts = [
        'date' => 'datetime'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function registrationDetails()
    {
        return $this->hasMany(RegistrationDetail::class);
    }
}
