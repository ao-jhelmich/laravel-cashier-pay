<?php

namespace Paynl\LaravelCashier\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'name',
        'price',
    ];
}
