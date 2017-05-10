<?php

namespace App\Repositories\Contracts;

interface PostRepositoryInterface
{
    public function findAll();

    public function find($id);

    public function findByUserId($id, $posted_by);

    public function findAllByUserId($posted_by);

    public function store($data);

    public function update($id, $posted_by, $data);

    public function delete($id);

    public function restore($id);

    public function destroy($id);

    public function searchThread($name);
}