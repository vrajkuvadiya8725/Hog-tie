<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientQuote extends Model
{
    protected $fillable = [
        'client_name',
        'quote',
    ];
}
