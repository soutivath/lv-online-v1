<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationDetail extends Model
{
    use HasFactory;

    protected $table = 'registration_details';
    protected $primaryKey = 'id';

    protected $fillable = [
        'registration_id',
        'major_id',
        'detail_price',
        'total_price'
    ];

    // Relationships
    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }
}
