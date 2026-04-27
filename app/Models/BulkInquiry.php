<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkInquiry extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'quantity',
        'address_line',
        'city',
        'state',
        'postal_code',
        'note',
    ];
}
