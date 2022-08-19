<?php

namespace App\Http\Controllers\Api\Lab;

use JWTAuth;
use App\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ServiceController extends Controller
{

    public function index(Request $req)
    {
        try {
            $service = Service::with('requirement')
            ->where('user_id',JWTAuth::user()->id)
            ->where('title', 'like', '%' . $req->title . '%');
            if($req->paginate == 'all'){
                $service = $service->get();
            }
            else{
                $service = $service->paginate($req->paginate);
            }
            $service->appends(['title'=>$req->title,'paginate'=>$req->paginate]);
            return response()->json([
                'success' => true,
                'services'=>$service,
            ],200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'something goes wrong'], 400);
        }
    }
    public function store(Request $request)
    {
        $data = JWTAuth::user();
        $request->validate([
            'title' => 'required',
            'requirement' => 'required',
            'price' => 'required|regex:/^(-)?[0-9]+(\.[0-9]{1,2})?$/',
            'description' => 'required',
        ]);
        try {
            $service = new Service;
            $service->user_id = $data->id;
            $service->title = $request->title;
            $service->requirement_id = $request->requirement;
            $service->price = $request->price;
            $service->description = $request->description;
            if($service->save()){
                return  response()->json([
                    'success' => true,
                    'message' => 'Service saved successfully'
                    ]);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'something goes wrong'], 400);
        }
    }

    public function show($id)
    {
        try {
            $data = JWTAuth::user();
            $service = Service::with('requirement')->where('user_id', $data->id)->where('id', $id)->first();
            if($service){
                return  response()->json([
                    'success' => true,
                    'service' => $service
                ]);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'something goes wrong'], 400);
        }
    }


    public function update(Request $request, $id)
    {
        $data = JWTAuth::user();
        $request->validate([
            'title' => 'required',
            'requirement' => 'required',
            'price' => 'required|regex:/^(-)?[0-9]+(\.[0-9]{1,2})?$/',
            'description' => 'required',
        ]);
        try {
            $service = Service::where('user_id',$data->id)->where('id',$id)->first();
            $service->title = $request->title;
            $service->requirement_id = $request->requirement;
            $service->price = $request->price;
            $service->description = $request->description;
            if($service->save()){
                return  response()->json([
                    'success' => true,
                    'message' => 'Service update successfully'
                ]);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'something goes wrong'], 400);
        }
    }

    public function destroy($id)
    {

    }
}
