<?php

namespace App\Http\Controllers;
use App\Http\Requests\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
//use Illuminate\Foundation\Auth\Access\AuthorizesResources;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $request;
    public function __construct($request){
        $this->request = $request;
       // $this->currentUser = $this->getCurrentUser($request);
    }
}
