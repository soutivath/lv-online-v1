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
        return $this->hasMany(Major::class, 'semester_id')->onDelete('cascade');
    }
}
