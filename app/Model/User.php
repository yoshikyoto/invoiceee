<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model implements \App\User\User
{
    protected $guarded = [
        'created_at',
        'updated_at',
    ];

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * ユーザーが見つからなかった場合は null
     * @param int $userId
     * @return User|null
     */
    public static function getById(int $userId): ?User
    {
        return static::find($userId);
    }

    public static function createUser(): ?User
    {
        return static::create();
    }
}
