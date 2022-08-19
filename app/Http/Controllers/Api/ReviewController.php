<?php

namespace App\Http\Controllers\Api;

use App\Brand;
use Exception;
use App\Common;
use App\Category;
use App\Requirement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    // store reviews 
    public function store(Request $req,$id)
    {

        return Common::reviewStore($req,$id,$req->rating,$req->description);          
    
    }

}
