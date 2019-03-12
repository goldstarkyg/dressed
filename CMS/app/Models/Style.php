<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Style extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'styles';

    protected $fillable = [
        'name',
        'status',
    ];

    public function user()
    {

        return $this->belongsTo('App\Models\User');

    }
}