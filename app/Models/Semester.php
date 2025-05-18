<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $table = 'semesters';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name'
    ];

    // Relationships
    public function majors()
    {
        return $this->hasMany(Major::class, 'semester_id');
        //->onDelete('cascade');
    }
    
    // Add relationship to Term through Major
    public function term()
    {
        // This assumes that a semester belongs to one term through majors
        // If the relationship is many-to-many, you might need to adjust this
        return $this->belongsToMany(Term::class, 'majors', 'semester_id', 'term_id')->withTimestamps();
    }
}
