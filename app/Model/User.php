<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model implements \App\User\User
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function getId(): int
    {
        return $this->id;
    }
}
