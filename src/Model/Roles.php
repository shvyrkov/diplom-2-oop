<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Facades\DB;

/**
 * 
 */
class Roles extends Model
{
    /**
     * Первичный ключ таблицы Roles.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    public $timestamps = false;

}
