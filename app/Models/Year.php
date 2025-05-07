<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    use HasFactory;

    protected $table = 'years';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name'
    ];

    // Relationships
    public function majors()
    {
        return $this->hasMany(Major::class, 'year_id');
        //->cascadeOnDelete();
    }
}
