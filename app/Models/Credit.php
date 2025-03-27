<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    protected $table = 'credits';
    protected $primaryKey = 'id';

    protected $fillable = [
        'price',
        'qty'
    ];

    // Relationships
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'credit_id');
    }
}
