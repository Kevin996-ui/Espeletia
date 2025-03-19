<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['department_name', 'contact_person', 'email'];

    /**
     * Obtener los correos de contacto como un array
     *
     * Si tienes múltiples correos separados por coma en el campo 'email',
     * esta función los convierte en un array.
     *
     * @return array
     */
    public function getContactEmailsAttribute()
    {
        return explode(',', $this->email); // Suponemos que los correos están separados por coma
    }
}
