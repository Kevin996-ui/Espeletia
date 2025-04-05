<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeyType extends Model
{
    use HasFactory;
    protected $table = 'key_types';
    protected $fillable = ['name', 'area', 'email'];

}
