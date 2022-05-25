<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Facades\DB;

/**
 * 
 */
class Post extends Model
{
    /**
     * Первичный ключ таблицы articles.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public $timestamps = false;
}
