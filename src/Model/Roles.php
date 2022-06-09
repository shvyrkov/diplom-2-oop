<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

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
