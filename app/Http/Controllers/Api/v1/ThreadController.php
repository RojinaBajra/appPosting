<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\Contracts\CommentRepositoryInterface;
use App\Repositories\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class ThreadController extends Controller
{
    protected $post, $comment;

    public function __construct(Request $request, PostRepositoryInterface $post, CommentRepositoryInterface $comment)
    {
        parent::__construct($request);
        $this->post = $post;
        $this->comment = $comment;
        
    }

    
    public function index()
    {
        try {
            $data = $this->post->findAll();
            if (!count($data->toArray())) {
                return response()->json(['success' => 0, 'message' => 'No threads'], 200);
            } else {
                return response()->json(['success' => 1, 'message' => 'Threads retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['success' => 0, 'error' => 1, "error_msg" => $e->getMessage()], $e->getCode());
        }
    }

    
    public function store()
    {
        try {
            $input = Input::only('topic', 'description');

            $rules = [
                'topic' => 'required|max:255',
                'description' => 'required'
            ];

            $validation = Validator::make($input, $rules);

            if ($validation->fails()) {
                return response()->json(['success' => 0, 'error' => 1, "error_msg" => $validation->errors()], 500);
            }

          // $input['posted_by'] = $this->currentUser->id;

            $data = $this->post->store($input);

            return response()->json(['success' => 1, 'message' => 'Thread added successfully', 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['success' => 0, 'error' => 1, "error_msg" => $e->getMessage()], $e->getCode());
        }
    }

   
    public function update($id)
    {
        try {
            $input = Input::only('topic', 'description');

            $rules = [
                'topic' => 'required|max:255',
                'description' => 'required'
            ];

            $validation = Validator::make($input, $rules);

            if ($validation->fails()) {
                return response()->json(['success' => 0, 'error' => 1, "error_msg" => $validation->errors()], 500);
            }

            $data = $this->thread->find($id);
            if (!$data) {
                return response()->json(['success' => 0, 'message' => 'No such thread'], 200);
            }

            $data = $this->thread->findByUserId($id, $this->currentUser->id);
            if (!$data) {
                return response()->json(['success' => 0, 'message' => 'User is not the owner of this thread'], 200);
            }

            $data = $this->thread->update($id, $this->currentUser->id, $input);

            return response()->json(['success' => 1, 'message' => 'Thread updated successfully', 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['success' => 0, 'error' => 1, "error_msg" => $e->getMessage()], $e->getCode());
        }
    }

   
    public function show($id)
    {
        try {
            $data = $this->thread->find($id);
            $username = $this->user->find($data->posted_by);
            $data->username = $username->username;
            foreach($data->replies as $eachReply){
                $username= $this->user->findActiveUser($eachReply->user_id);
                $eachReply->username = $username->username;
            }
            if (!$data) {
                return response()->json(['success' => 0, 'message' => 'No such thread'], 200);
            } else {
                return response()->json(['success' => 1, 'message' => 'Thread retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['success' => 0, 'error' => 1, "error_msg" => $e->getMessage()], $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $data = $this->thread->find($id);
            if (!$data) {
                return response()->json(['success' => 0, 'message' => 'No such thread'], 200);
            }
            if (!($this->currentUser->is_admin || $data->posted_by === $this->currentUser->id)) {

                return response()->json(['success' => 0, 'message' => 'User is not the owner of this thread'], 200);
            }

            $this->thread->delete($id);

            return response()->json(['success' => 1, 'message' => 'Thread deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['success' => 0, 'error' => 1, "error_msg" => $e->getMessage()], $e->getCode());
        }
    }

   
    public function restore($id)
    {
        try {
            $data = $this->thread->restore($id);
            if ($data) {
                return response()->json(['success' => 1, 'message' => 'Thread restored successfully', 'data' => $data], 200);
            } else {
                return response()->json(['success' => 0, 'message' => 'No such deleted thread'], 200);
            }
        } catch (Exception $e) {
            return response()->json(['success' => 0, 'error' => 1, "error_msg" => $e->getMessage()], $e->getCode());
        }
    }

   
    public function destroyThread($id)
    {
        try {
            $data = $this->thread->destroy($id);
            if ($data) {
                return response()->json(['success' => 1, 'message' => 'Thread deleted permanently'], 200);
            } else {
                return response()->json(['success' => 0, 'message' => 'No such thread'], 200);
            }
        } catch (Exception $e) {
            return response()->json(['success' => 0, 'error' => 1, "error_msg" => $e->getMessage()], $e->getCode());
        }
    }

   
    public function comment($id)
    {
        try {
            if (!$this->post->find($id)) {
                return response()->json(['success' => 1, 'message' => 'No such thread'], 200);
            }
            $input = Input::only('reply');

            $rules = [
                'reply' => 'required'
            ];

            $validation = Validator::make($input, $rules);

            if ($validation->fails()) {
                return response()->json(['success' => 0, 'error' => 1, "error_msg" => $validation->errors()], 500);
            }
            $input['post_id'] = $id;
            //$input['user_id'] = $this->currentUser->id;

            $data = $this->comment->store($input);

            return response()->json(['success' => 1, 'message' => 'Replied to thread successfully', 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['success' => 0, 'error' => 1, "error_msg" => $e->getMessage()], $e->getCode());
        }
    }

   
    public function viewReplies($id)
    {
        try {
            if (!$this->post->find($id)) {
                return response()->json(['success' => 0, 'message' => 'No such post'], 200);
            }
            $data = $this->comment->findAllByThreadId($id);
            if (!count($data->toArray())) {
                return response()->json(['success' => 0, 'message' => 'No comment for this thread'], 200);
            } else {
                return response()->json(['success' => 1, 'message' => 'comments retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['success' => 0, 'error' => 1, "error_msg" => $e->getMessage()], $e->getCode());
        }
    }

   
    public function viewReplyById($id, $reply_id)
    {
        try {
            $data = $this->reply->findByThreadId($reply_id, $id);
            $username = $this->user->findActiveUser($this->currentUser->id);
            $data->username = $username->username;
            if (!$data) {
                return response()->json(['success' => 0, 'message' => 'No such reply for this thread'], 200);
            } else {
                return response()->json(['success' => 1, 'message' => 'Reply retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['success' => 0, 'error' => 1, "error_msg" => $e->getMessage()], $e->getCode());
        }
    }

    
    public function editComment($id, $reply_id)
    {
        try {
            if (!$this->post->find($id)) {
                return response()->json(['success' => 1, 'message' => 'No such thread'], 200);
            }
            $input = Input::only('reply');

            $rules = [
                'reply' => 'required'
            ];

            $validation = Validator::make($input, $rules);

            if ($validation->fails()) {
                return response()->json(['success' => 0, 'error' => 1, "error_msg" => $validation->errors()], 500);
            }

            $data = $this->comment->findByThreadId($id, $reply_id);

            if (!$data) {
                return response()->json(['success' => 0, 'message' => 'No such reply'], 200);
            }

           $data = $this->comment->update($id, $reply_id, $input);

            return response()->json(['success' => 1, 'message' => 'Reply edited successfully', 'data' => $data], 200);
        } catch (Exception $e) {
            response()->json(['success' => 0, 'error' => 1, 'error_msg' => $e->getMessage()], $e->getCode());
        }
    }

    
    public function deleteReply($id, $reply_id)
    {
        try {
            $data = $this->comment->findByThreadId($id, $reply_id);
            if (!$data) {
                return response()->json(['success' => 0, 'message' => 'No such reply'], 200);
            }

            $this->comment->destroy($reply_id);
            return response()->json(['success' => 1, 'message' => 'Comment deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['success' => 0, 'error' => 1, "error_msg" => $e->getMessage()], $e->getCode());
        }
    }

    public function searchThread()
    {
        try {
            $input = Input::only('name');
            $rules = [
                'name' => 'required'
            ];
            $validate = Validator::make($input, $rules);

            if($validate->fails()) {
                return response()->json(['success' => 0, 'error' => 1, 'error_msg' => $validate->errors()], 500);
            }

            $thread = $this->thread->searchThread($input['name']);
            if (!count($thread)) {
                return response()->json(['success' => 0, 'message' => 'No Threads found']);
            }

            return response()->json(['success' => 1, 'message' => 'Threads retrieved successfully', 'data' => $thread], 200);
        } catch (Exception $e) {
            return response()->json(["success" => 0, "error" => 1, "error_msg" => $e->getMessage()], $e->getCode());
        }
    }

}