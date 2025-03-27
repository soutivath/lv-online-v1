<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;

    protected $table = 'majors';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'semester_id',
        'term_id',
        'year_id',
        'tuition_id',
        'sokhn'
    ];

    // Relationships
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function term()
    {
        return $this->belongsTo(Term::class, 'term_id');
    }

    public function year()
    {
        return $this->belongsTo(Year::class, 'year_id');
    }

    public function tuition()
    {
        return $this->belongsTo(Tuition::class, 'tuition_id');
    }

    public function registrationDetails()
    {
        return $this->hasMany(RegistrationDetail::class, 'major_id')->cascadeOnDelete();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'major_id')->cascadeOnDelete();
    }

    public function upgrades()
    {
        return $this->hasMany(Upgrade::class, 'major_id')->cascadeOnDelete();
    }
}
