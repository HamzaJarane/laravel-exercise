<?php

namespace App\Importers\Product;

use App\Interfaces\ImporterInterface;

class JsonImporter implements ImporterInterface
{
    /**
     * Handle the import logic
     * @param string $source
     * @return void
     */
    public function import(string $source): void
    {
        $isVerified = $this->verify($source);        
        // logic ...
    }

    /**
     * Verify the input source
     * @param string $source
     * @return bool
     */
    public function verify(string $source): bool
    {
        return true;
    }
}