<?php

namespace App\Interfaces;

interface ImporterInterface
{
    public function import(string $source): void;

    public function verify(string $source): bool;
}