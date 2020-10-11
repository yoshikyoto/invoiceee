<?php

namespace App\Invoice;

use Carbon\Carbon;

class HerokuInvoice
{
    private string $id;

    private int $number;

    private Carbon $createdAt;


    /**
     * HerokuInvoice constructor.
     * @param string $id
     * @param Carbon $createdAt
     */
    public function __construct(
        string $id,
        int $number,
        Carbon $createdAt
    ) {
        $this->id = $id;
        $this->number = $number;
        $this->createdAt = $createdAt;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }
}
