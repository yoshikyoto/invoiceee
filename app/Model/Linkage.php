<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Linkage extends Model
{
    protected $guarded = [
        'created_at',
        'updated_at',
    ];

    const HEROKU = 'heroku';

    public function getUserId(): int
    {
        return $this->id;
    }

    public function getServiceAccountId(): string
    {
        return $this->service_account_id;
    }

    public function getServiceName(): string
    {
        return $this->service_name;
    }

    public static function createHeroku(
        int $id,
        string $herokuAccountId,
        string $token
    ) {
        static::create([
            'id' => $id,
            'service_name' => static::HEROKU,
            'service_account_id' => $herokuAccountId,
            'api_access_token' => $token,
        ]);
    }

    /**
     * @param int $id
     * @return Linkage[] Linkage の Collection
     */
    public static function getAllLinkages(int $id)
    {
        return static::where('id', $id)->get();
    }
}
