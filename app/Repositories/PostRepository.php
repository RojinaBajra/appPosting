<?php

namespace App\Repositories;

use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Models\Post;
use App\Repositories\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Log;

class PostRepository implements PostRepositoryInterface
{
    protected $post, $comment;

    public function __construct(Post $post, Comment $comment)
    {
        $this->post = $post;
        $this->comment = $comment;
    }

    public function findAll()
    {
        try {
            return $this->post->with('comment')->withTrashed()->get();
        } catch (QueryException $e) {
            Log::error("PostRepository|findAll()|Query error: " . $e->getMessage());
            throw new Exception ("Query error", 500);
        } catch (Exception $e) {
            Log::error("PostRepository|findAll()|Server error: " . $e->getMessage());
            throw new Exception ("Server error", 500);
        }
    }

    public function find($id)
    {
        try {
            return $this->post->with('comment')->find($id);

        } catch (QueryException $e) {
            Log::error("PostRepository|find()|Query error: " . $e->getMessage());
            throw new Exception ("Query error", 500);
        } catch (Exception $e) {
            Log::error("PostRepository|find()|Server error: " . $e->getMessage());
            throw new Exception ("Server error", 500);
        }
    }

    public function findByUserId($id, $posted_by)
    {
        try {
            $thread=  $this->thread->where('posted_by', $posted_by)->where('id', $id)->with('user')->first();
            return $thread;
        } catch (QueryException $e) {
            Log::error("ThreadRepository|findByUserId()|Query error: " . $e->getMessage());
            throw new Exception ("Query error", 500);
        } catch (Exception $e) {
            Log::error("ThreadRepository|findByUserId()|Server error: " . $e->getMessage());
            throw new Exception ("Server error", 500);
        }
    }

    public function findAllByUserId($posted_by)
    {
        try {
            return $this->thread->where('posted_by', $posted_by)->get();
        } catch (QueryException $e) {
            Log::error("ThreadRepository|findAllByUserId()|Query error: " . $e->getMessage());
            throw new Exception ("Query error", 500);
        } catch (Exception $e) {
            Log::error("ThreadRepository|findAllByUserId()|Server error: " . $e->getMessage());
            throw new Exception ("Server error", 500);
        }
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $this->post->fill($data)->save();
            DB::commit();
            return $this->post->orderBy('created_at', 'desc')->first();
        } catch (QueryException $e) {
            Log::error("PostRepository|store()|Query error: " . $e->getMessage());
            DB::rollback();
            throw new Exception ("Query error", 500);
        } catch (Exception $e) {
            Log::error("PostRepository|store()|Server error: " . $e->getMessage());
            DB::rollback();
            throw new Exception ("Server error", 500);
        }
    }

    public function update($id, $posted_by, $data)
    {
        DB::beginTransaction();
        try {
            $thread = $this->thread->where('id', $id)->where('posted_by', $posted_by)->with('user')->first();

            if ($thread) {
                $thread->fill($data)->save();
                DB::commit();
                return $this->thread->where('posted_by', $posted_by)->orderBy('updated_at', 'desc')->with('user')->first();
            } else {
                throw new Exception ("error", 500);
            }
        } catch (QueryException $e) {
            Log::error("ThreadRepository|update()|Query error: " . $e->getMessage());
            DB::rollback();
            throw new Exception ("Query error", 500);
        } catch (Exception $e) {
            Log::error("ThreadRepository|update()|Server error: " . $e->getMessage());
            DB::rollback();
            throw new Exception ("Server error", 500);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $temp = $this->thread->find($id);
            if ($temp) {
                $temp->replies()->delete();
                $temp->delete();
                DB::commit();
            }
        } catch (QueryException $e) {
            Log::error("ThreadRepository|delete()|Query error: " . $e->getMessage());
            DB::rollback();
            throw new Exception("Query error", 500);
        } catch (Exception $e) {
            Log::error("ThreadRepository|delete()|Server error: " . $e->getMessage());
            DB::rollback();
            throw new Exception("Server error", 500);
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $temp = $this->thread->withTrashed()->find($id);
            if ($temp && $temp->trashed()) {
                $temp->replies()->restore();
                $temp->restore();
                DB::commit();
                return $this->thread->with('replies')->find($id);
            } else {
                return false;
            }
        } catch (QueryException $e) {
            DB::rollback();
            Log::error("ThreadRepository|restore()|Query error: " . $e->getMessage());
            throw new Exception ("Query error", 500);
        } catch (Exception $e) {
            DB::rollback();
            Log::error("ThreadRepository|restore()|Server error: " . $e->getMessage());
            throw new Exception ("Server error", 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $temp = $this->thread->withTrashed()->find($id);
            if ($temp) {
                $temp->replies()->forceDelete();
                $temp->forceDelete();
                DB::commit();
                return true;
            } else {
                return false;
            }
        } catch (QueryException $e) {
            DB::rollback();
            Log::error("ThreadRepository|destroy()|Query error: " . $e->getMessage());
            throw new Exception ("Query error", 500);
        } catch (Exception $e) {
            DB::rollback();
            Log::error("ThreadRepository|destroy()|Server error: " . $e->getMessage());
            throw new Exception ("Server error", 500);
        }
    }

    public function searchThread($name)
    {
        try {
            $thread = $this->thread->where('topic', 'like', '%' . $name . '%')->with('user')->get();
            return $thread;
        } catch (QueryException $e) {
            Log::error("ThreadRepository|searchThread()|Query error: " . $e->getMessage());
            throw new Exception ("Query error", 500);
        } catch (Exception $e) {
            Log::error("ThreadRepository|searchThread()|Query error: " . $e->getMessage());
            throw new Exception ("Server error", 500);
        }
    }
}