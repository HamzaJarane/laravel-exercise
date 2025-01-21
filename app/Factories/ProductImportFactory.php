<?php

namespace App\Factories;

use App\Interfaces\ImporterInterface;
use Exception;

class ProductImportFactory
{
    private static array $importers = [];

    public static function register(string $type, string $importerClass): void
    {
        self::$importers[$type] = $importerClass;
    }

    public function create(string $type): ImporterInterface
    {
        if (!isset(self::$importers[$type])) {
            throw new Exception("Unsupported import type: {$type}");
        }

        return new self::$importers[$type]();
    }
}