<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
                'visitor_name',
                'visitor_email',
                'visitor_mobile_no',
                'visitor_address',
                'visitor_meet_person_name',
                'visitor_department',
                'visitor_reason_to_meet',
                'visitor_enter_time',
                'visitor_outing_remark',
                'visitor_out_time',
                'visitor_status',
                'visitor_enter_by'
    ];
}
