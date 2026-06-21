<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationMember extends Model
{
    protected $fillable = [
        'name',
        'position',
        'level',
        'description',
        'photo',
        'sort_order',
    ];
}
