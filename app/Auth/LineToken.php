<?php


namespace App\Auth;


class LineToken extends OAuth2Token
{
    /**
     * @var string LINEログイン連携から、アカウントの情報を取得するのに必要なトークン
     */
    private string $idToken;

    public function __construct(string $token, string $idToken)
    {
        parent::__construct($token);
        $this->idToken = $idToken;
    }

    public function getIdToken(): string
    {
        return $this->idToken;
    }
}
