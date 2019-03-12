<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'brand';

    protected $fillable = [
        'brand',
        'status',
    ];

    public function user()
    {

        return $this->belongsTo('App\Models\User');

    }
}