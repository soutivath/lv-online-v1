<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpgradeDetail extends Model
{
    use HasFactory;

    protected $table = 'upgrade_details';
    protected $primaryKey = 'id';

    protected $fillable = [
        'subject_id',
        'detail_price',
        'total_price'
    ];

    // Relationships
    public function upgrade()
    {
        return $this->belongsTo(Upgrade::class, 'upgrade_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
