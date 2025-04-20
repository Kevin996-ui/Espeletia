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
        'department_id',
        'visitor_card',
        'card_id',
        'visitor_photo',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function card()
    {
        return $this->belongsTo(Card::class, 'card_id');
    }

}
