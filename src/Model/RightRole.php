<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RightRole
 * @package App\Model
 */
class RightRole extends Model
{
    /**
     * Первичный ключ таблицы RightRole.
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
