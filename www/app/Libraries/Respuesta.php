<?php

declare(strict_types=1);

namespace Com\Daw2\Libraries;

class Respuesta
{
    private int $status;
    private ?array $data;

    public function __construct(int $status, ?array $data)
    {
        $this->status = $status;
        $this->data = $data;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(?array $data): void
    {
        $this->data = $data;
    }

    public function hasData(): bool
    {
        return !empty($this->data);
    }
}
