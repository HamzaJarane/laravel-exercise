<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ImportProductsCommandTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_should_not_throw_an_exception_for_any_row(): void
    {
        $exitCode = Artisan::call(
            'import:products', [
                '--api' => true,
                '--sku' => true,
                '--throw' => true,
                '--soft'=> true,
                '--max' => 100,
            ]
        );

        $this->assertEquals(0, $exitCode, 'The command failed or threw an error.');
    }
}
