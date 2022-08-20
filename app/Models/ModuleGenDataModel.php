<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleGenDataModel extends Model
{
    use HasFactory;
    protected $table = "module_gen_data";
    protected $fillable = ['table_name', 'query', 'spesial_conditions', 'flag_active'];
    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
