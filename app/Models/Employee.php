<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'sername',
        'birthday',
        'date',
        'gender',
        'address',
        'tell',
        'picture',
        'user_id'
    ];

    // Relationships
    public function registrations()
    {
        return $this->hasMany(Registration::class, 'employee_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'employee_id');
    }

    public function upgrades()
    {
        return $this->hasMany(Upgrade::class, 'employee_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
