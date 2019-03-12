<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'posts';

    public function user()
    {

        return $this->belongsTo('App\Models\User');

    }
}