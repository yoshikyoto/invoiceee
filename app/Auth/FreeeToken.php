<?php


namespace App\Auth;


class FreeeToken
{
    private string $token;

    /**
     * FreeeToken constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
