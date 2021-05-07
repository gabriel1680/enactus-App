<?php

namespace App\Model;

interface IModel
{
    public function find(string $terms, string $params, string $columns = "*"): ?IModel;
    public function findById($id, string $columns = "*"): ?IModel;
    public function count(): ?string;
    public function save(): ?IModel;
    public function destroy(): ?IModel;
}
