<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rights
 * @package App\Model
 */
class Rights extends Model
{
    /**
     * Первичный ключ таблицы Rights.
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
