<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tuition extends Model
{
    use HasFactory;

    protected $table = 'tuitions';
    protected $primaryKey = 'id';

    protected $fillable = [
        'price'
    ];

    // Relationships
    public function majors()
    {
        return $this->hasMany(Major::class, 'tuition_id');
    }
}
