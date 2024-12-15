<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        "name",
        "path",
        "type",
    ];

}