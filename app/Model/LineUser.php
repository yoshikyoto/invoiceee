<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LineUser extends Model
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
     * Line の情報が見つからなかった場合は null
     * @param int $lineUserId
     * @return LineUser|null
     */
    public static function getByLineUserId(int $lineUserId): ?LineUser
    {
        return static::where('line_user_id', $lineUserId)->first();
    }

    public static function createLineUser(
        int $id,
        int $lineUesrId,
        string $token
    ): ?FreeeUser {
        static::getByLineUserId($lineUesrId);
        return static::create([
            'id' => $id,
            'line_user_id' => $lineUesrId,
            'line_token' => $token
        ]);
    }

    public static function updateLineUserId(int $id, string $lineUserId)
    {
        static::updateOrCreate(
            ['id' => $id],
            [
                'id' => $id,
                'line_user_id' => $lineUserId,
            ]
        );
    }

    public static function updateToken(int $id, string $token): LineUser
    {
        return static::where('id', $id)
            ->update('line_token', $token);
    }
}
