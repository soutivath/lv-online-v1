<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'credit_id'
    ];

    // Relationships
    public function credit()
    {
        return $this->belongsTo(Credit::class, 'credit_id');
    }

    public function upgradeDetails()
    {
        return $this->hasMany(UpgradeDetail::class, 'subject_id')->onDelete('cascade');
    }

    public function majors()
    {
        return $this->belongsToMany(Major::class);
    }
}
