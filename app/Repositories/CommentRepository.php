<?php

namespace App\Repositories;

use App\Repositories\Contracts\CommentRepositoryInterface;
use App\Repositories\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Log;

class CommentRepository implements CommentRepositoryInterface
{
    protected $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function findByThreadId($id, $reply_id)
    {
        try {
            //dd($id, $thread_id);
            return $this->comment->where('post_id', $id)->where('id', $reply_id)->get();
        } catch (QueryException $e) {
            Log::error("CommentRepository|findByThreadId()|Query error: " . $e->getMessage());
            throw new Exception ("Query error", 500);
        } catch (Exception $e) {
            Log::error("CommentRepository|findByThreadId()|Server error: " . $e->getMessage());
            throw new Exception ("Server error", 500);
        }
    }

    public function findByUserId($id, $thread_id, $user_id)
    {
        try {
            return $this->reply->where('thread_id', $thread_id)->where('user_id', $user_id)->where('id', $id)->first();
        } catch (QueryException $e) {
            Log::error("ThreadRepliesRepository|findByThreadId()|Query error: " . $e->getMessage());
            throw new Exception ("Query error", 500);
        } catch (Exception $e) {
            Log::error("ThreadRepliesRepository|findByThreadId()|Server error: " . $e->getMessage());
            throw new Exception ("Server error", 500);
        }
    }

    public function findAllByThreadId($thread_id)
    {
        try {
            return $this->comment->where('post_id', $thread_id)->get();
        } catch (QueryException $e) {
            Log::error("PostRepositoryInterface|findAllByThreadId()|Query error: " . $e->getMessage());
            throw new Exception ("Query error", 500);
        } catch (Exception $e) {
            Log::error("PostRepositoryInterface|findAllByThreadId()|Server error: " . $e->getMessage());
            throw new Exception ("Server error", 500);
        }
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $this->comment->fill($data)->save();
            DB::commit();
            return $this->comment->where('post_id', $data['post_id'])->orderBy('created_at', 'desc')->first();
        } catch (QueryException $e) {
            Log::error("CommentRepository|store()|Query error: " . $e->getMessage());
            DB::rollback();
            throw new Exception ("Query error", 500);
        } catch (Exception $e) {
            Log::error("CommentRepository|store()|Server error: " . $e->getMessage());
            DB::rollback();
            throw new Exception ("Server error", 500);
        }
    }

    public function update($id, $reply_id, $data)
    {
        DB::beginTransaction();
        try {
            $reply = $this->comment->where('id', $reply_id)->where('post_id', $id)->first();
            if ($reply) {
                $reply->fill($data)->save();
                DB::commit();
                return $this->comment->where('id', $reply_id)->where('post_id', $id)->first();
            } else {
                throw new Exception ("error", 500);
            }
        } catch (QueryException $e) {
            Log::error("CommentRepository|update()|Query error: " . $e->getMessage());
            DB::rollback();
            throw new Exception ("Query error", 500);
        } catch (Exception $e) {
            Log::error("CommentRepository|update()|Server error: " . $e->getMessage());
            DB::rollback();
            throw new Exception ("Server error", 500);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $temp = $this->reply->find($id);
            if ($temp) {
                $temp->delete();
                DB::commit();
            }
        } catch (QueryException $e) {
            Log::error("ThreadRepliesRepository|delete()|Query error: " . $e->getMessage());
            DB::rollback();
            throw new Exception("Query error", 500);
        } catch (Exception $e) {
            Log::error("ThreadRepliesRepository|delete()|Server error: " . $e->getMessage());
            DB::rollback();
            throw new Exception("Server error", 500);
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $temp = $this->reply->withTrashed()->find($id);
            if ($temp) {
                $temp->restore();
                DB::commit();
            }
        } catch (QueryException $e) {
            Log::error("ThreadRepliesRepository|restore()|Query error: " . $e->getMessage());
            DB::rollback();
            throw new Exception("Query error", 500);
        } catch (Exception $e) {
            Log::error("ThreadRepliesRepository|restore()|Server error: " . $e->getMessage());
            DB::rollback();
            throw new Exception("Server error", 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $temp = $this->comment->withTrashed()->find($id);
            if ($temp) {
                $temp->forceDelete();
                DB::commit();
            }
        } catch (QueryException $e) {
            Log::error("ThreadRepliesRepository|destroy()|Query error: " . $e->getMessage());
            DB::rollback();
            throw new Exception("Query error", 500);
        } catch (Exception $e) {
            Log::error("ThreadRepliesRepository|destroy()|Server error: " . $e->getMessage());
            DB::rollback();
            throw new Exception("Server error", 500);
        }
    }
    
    public function destroyByThreadId($thread_id)
    {
        DB::beginTransaction();
        try {
            $this->reply->withTrashed()->where('thread_id', $thread_id)->get()->each(function ($item, $key) {
                $item->forceDelete();
            });
            DB::commit();
        } catch (QueryException $e) {
            Log::error("ThreadRepliesRepository|destroyByThreadId()|Query error: " . $e->getMessage());
            DB::rollback();
            throw new Exception("Query error", 500);
        } catch (Exception $e) {
            Log::error("ThreadRepliesRepository|destroyByThreadId()|Server error: " . $e->getMessage());
            DB::rollback();
            throw new Exception("Server error", 500);
        }
    }
}