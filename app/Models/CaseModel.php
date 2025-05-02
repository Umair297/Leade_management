<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseModel extends Model
{
    use HasFactory;

    protected $table = 'lead';

    protected $fillable = [
        'items',
        'prestamo',
        'cod_cliente',
        'identificacion',
        'nombre_cliente',
        'saldo',
        'dias_mora',
        'morosidad',
        'lugar_empleo',
        'ocupacion',
        'prov',
        'direccion',
        'status',
        'follow_up_date',
    ];
public function assignedEmployees()
{
    return $this->belongsToMany(User::class, 'assignments', 'case_id' , 'user_id');
}

public function assignments()
{
    return $this->hasMany(Assignment::class, 'case_id', 'user_id'); // This will relate cases to assignments
}

// CaseModel.php
public function agents()
{
    return $this->belongsToMany(User::class, 'assignments', 'case_id', 'user_id');
}


}


