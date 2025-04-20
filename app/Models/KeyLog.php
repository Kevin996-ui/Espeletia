<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeyLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'key_type_id',
        'name_taken',
        'identity_card_taken',
        'area',
        'key_code',
        'key_taken_at',
        'name_returned',
        'identity_card_returned',
        'returned_photo',
        'key_returned_at',
    ];
}
