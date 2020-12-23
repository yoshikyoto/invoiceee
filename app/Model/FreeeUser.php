<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FreeeUser extends Model
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
     * Freee の情報が見つからなかった場合は null
     * @param int $freeeUserId
     * @return FreeeUser|null
     */
    public static function getByFreeeUserId(int $freeeUserId): ?FreeeUser
    {
        return static::where('freee_user_id', $freeeUserId)->first();
    }

    public static function createFreeeUser(
        int $id,
        int $freeeUesrId,
        string $token
    ): ?FreeeUser {
        static::getByFreeeUserId($freeeUesrId);
        return static::create([
            'id' => $id,
            'freee_user_id' => $freeeUesrId,
            'freee_token' => $token
        ]);
    }

    public static function createOrUpdateFreeeUser(
        int $id,
        string $freeeUserId,
        string $token
    ): void {
        static::updateOrCreate(
            ['id' => $id],
            [
                'id' => $id,
                'freee_user_id' => $freeeUserId,
                'freee_token' => $token,
            ]);
    }
}
