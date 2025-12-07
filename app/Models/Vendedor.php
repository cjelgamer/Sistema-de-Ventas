<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Vendedor extends Authenticatable
{
    use Notifiable;

    protected $table = 'Vendedor';
    protected $primaryKey = 'ID'; // Asegúrate de que coincida con tu columna de ID
    public $timestamps = false; // Desactivar timestamps ya que la tabla no tiene created_at/updated_at

    protected $fillable = [
        'Nombre', 'Telefono', 'Estado', 'Rol', 'Contraseña',
    ];

    protected $hidden = [
        'Contraseña',
    ];

    public function getAuthPassword()
    {
        return $this->Contraseña;
    }
}
