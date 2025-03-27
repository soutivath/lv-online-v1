<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'sername',
        'gender',
        'birthday',
        'nationality',
        'tell',
        'address',
        'user_id',
        'picture',
        'score', // Changed from document_score to score
    ];

    protected $casts = [
        'birthday' => 'date'
    ];

    // Relationships
    public function registrations()
    {
        return $this->hasMany(Registration::class, 'student_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'student_id');
    }

    public function upgrades()
    {
        return $this->hasMany(Upgrade::class, 'student_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
