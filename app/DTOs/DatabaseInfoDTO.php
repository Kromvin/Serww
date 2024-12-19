<?php

namespace App\DTOs;

use Illuminate\Support\Facades\DB;

class DatabaseInfoDTO
{
    public function __construct(
        public string $connection,
        public string $databaseName
    ) {}

    public static function fromConfig(): self
    {
        return new self(
            connection: config('database.default'),
            databaseName: DB::connection()->getDatabaseName()
        );
    }

    public function toArray(): array
    {
        return [
            'connection' => $this->connection,
            'database_name' => $this->databaseName,
        ];
    }
}