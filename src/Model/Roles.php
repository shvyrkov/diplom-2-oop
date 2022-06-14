<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Roles
 * @package App\Model
 */
class Roles extends Model
{
    /**
     * Первичный ключ таблицы Roles.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
