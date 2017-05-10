<?php

namespace App\Repositories\Contracts;

interface CommentRepositoryInterface
{
    public function findByThreadId($id, $reply_id);

    public function findByUserId($id, $thread_id, $user_id);

    public function findAllByThreadId($thread_id);

    public function store($data);

    public function update($id, $reply_id, $data);

    public function delete($id);

    public function restore($id);

    public function destroy($id);

    public function destroyByThreadId($thread_id);
}