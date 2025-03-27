<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upgrade extends Model
{
    use HasFactory;

    protected $table = 'upgrades';
    protected $primaryKey = 'id';

    protected $fillable = [
        'date',
        'student_id',
        'major_id',
        'employee_id',
        'payment_status',
        'payment_proof'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function upgradeDetails()
    {
        return $this->hasMany(UpgradeDetail::class);
    }
}
