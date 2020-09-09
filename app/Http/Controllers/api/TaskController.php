<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\TasksRequest;
use App\Task;
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
        return response(['status' => true, 'data' => $index], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TasksRequest $request)
    {
        $item = $request->item;
        $tag = $request->tag;
        // $image = $request->image;
        $CardId = $request->card_id;
        $description = $request->description;
        $user = $request->UserData->username;

        if (!Card::find($CardId)) {
            return response()->json(['status' => false, 'error' => 'card serch not found'], 400);
        }
        $store = Task::create([
            'item' => $item, 'status' => false,
            'create_user' => $user, 'update_user' => $user,
            'description' => $description, 'tag' => $tag,
            'card_id' => $CardId,
        ]);
        return response()->json(['status' => true, 'task_data' => $store], 201);
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
            return response()->json(['status' => false, 'error' => 'item search not found'], 400);
        }
        return response(['status' => true, 'task_data' => $show], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TasksRequest $request, $id)
    {
        $item = $request->item;
        $user = $request->UserData->username;
        $tag = $request->tag;
        // $image = $request->image;
        $description = $request->description;
        $status = $request->status;
        $CardId = $request->card_id;
        $update = Task::find($id);
        if (!$update) {
            return response()->json(['status' => false, 'error' => 'task search not found'], 400);
        }
        if (!Card::find($CardId)) {
            return response()->json(['status' => false, 'error' => 'card search not found'], 400);
        }

        $update->update([
            'item' => "$item", 'update_user' => $user,
            'item' => $item, 'status' => $status,
            'update_user' => $user,
            'description' => $description, 'tag' => $tag,
            // 'image' => $image, 
            'card_id' => $CardId,
        ]);
        return response()->json(['status' => true, 'task_data' => $update], 200);
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
            return response()->json(['status' => true, 'error' => 'task search not found'], 400);
        }
        $delete->delete();
        return response()->json(['status' => true], 200);
        // return 'delete successfully';
    }
    public function upload(Request $request)
    {
        dd($request);
        // $User = Users::find(2);
        // $User::create(['image' => ''])
    }
}
