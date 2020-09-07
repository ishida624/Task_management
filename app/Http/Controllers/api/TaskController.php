<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\TodosRequest;
use App\Task;
use App\Users;
use App\Card;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $index = Task::all();
        // return $index;
        return response($index, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $item = $request->item;
        $tag = $request->tag;
        $image = $request->image;
        $CardId = $request->card_id;
        $description = $request->description;
        $user = $request->UserData->username;
        $store = Task::create([
            'item' => $item, 'status' => false,
            'create_user' => $user, 'update_user' => $user,
            'description' => $description, 'tag' => $tag,
            'image' => $image, 'card_id' => $CardId,
        ]);
        return response()->json(['status' => 'true', 'data' => $store], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $show = Task::find($id);
        if (!$show) {
            return response()->json(['message' => 'bad request', 'reason' => 'item search not found'], 400);
        }
        // return $show;
        return response($show, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TodosRequest $request, $id)
    {
        $item = $request->item;
        $user = $request->UserData->admin;
        // if (!$item) {
        //     return response()->json(['message' =>'bad request' , 'reason' => 'item can not null' ], 400);
        // }
        $update = Task::find($id);
        if (!$update) {
            return response()->json(['message' => 'bad request', 'reason' => 'item search not found'], 400);
        }
        // $validator = $request->getValidatorInstance();
        // if ($validator->fails()) {
        //     $errorMessage = $validator->getMessageBag()->getMessages();
        //     return response()->json(['message' =>'bad request' , 'error' =>$errorMessage], 400);
        // }
        $update->update(['item' => "$item", 'update_user' => $user]);
        return response()->json(['message' => 'update successfully', 'content' => $update], 200);
        // return 'update successfully';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Task::find($id);
        if (!$delete) {
            return response()->json(['message' => 'bad request', 'reason' => 'item search not found'], 400);
        }
        $delete->delete();
        return response()->json(['message' => 'delete successfully'], 200);
        // return 'delete successfully';
    }
    public function upload(Request $request)
    {
        dd($request);
        // $User = Users::find(2);
        // $User::create(['image' => ''])
    }
}
