<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewVisitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_name',
        'visitor_company',
        'visitor_identity_card',
        'visitor_enter_time',
        'visitor_out_time',
        'visitor_reason_to_meet',
        'visitor_photo',
        'department_id',
    ];
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'visitor_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class);  // Relación con el modelo Department
    }
}
