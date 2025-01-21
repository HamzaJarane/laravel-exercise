<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface BaseServiceInterface
{
    public function getModel(): Model;

    public function all();

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function find($id);
}