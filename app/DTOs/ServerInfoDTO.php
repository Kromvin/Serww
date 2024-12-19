<?php

namespace App\DTOs;

class ServerInfoDTO
{
    public function __construct(
        public string $phpVersion,
    ) {}

    public function toArray(): array
    {
        return [
            'php_version' => $this->phpVersion,
        ];
    }
}
