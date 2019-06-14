<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientContact extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone_number', 'alternative_phone_number', 'alternative_email',
        'address', 'city', 'zip_code'
    ];
}
