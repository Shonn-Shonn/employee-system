<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_of_application','education',
        'employee_id','experience','other_info'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
