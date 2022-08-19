<?php

namespace App\Http\Controllers\Api;

use App\Brand;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Requirement;
use Exception;

class CommonController extends Controller
{
   
    public function getCategoryOrBrand(Request $req)
    {
        try {
        $data = [];
        if($req->type == 'all' || $req->type == 'category'){
            $data['categories'] = Category::get();
        }
        if($req->type == 'all' || $req->type == 'brand'){
            $data['brands'] = Brand::get();
        }
        if($req->type == 'all' || $req->type == 'requirement'){
            $data['requirements'] = Requirement::get();
        }
            return response()->json([
                'success' => true,
                'data'=>$data,
            ],200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'something goes wrong'], 400);
        }
    }


}
