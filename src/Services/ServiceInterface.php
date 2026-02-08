<?php

namespace Cheney\AdminSystem\Services;

interface ServiceInterface
{
    public function index(array $params = []);

    public function show(int $id);

    public function store(array $data);

    public function update(int $id, array $data);

    public function destroy(int $id);
}