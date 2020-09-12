<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TasksRequest;
use App\Task;
use App\Card;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

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
        // foreach ($index as  $value) {
        //     $path = $value->image;
        //     if (isset($value->image)) {
        //         // dd('../storage/app/' . $path);
        //         $file = file_get_contents('../storage/app/' . $path);
        //     }
        // 
        // return response()->file('../storage/app/image/task/test.jpeg');
        return response(['status' => true, 'task_data' => $index], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TasksRequest $request)
    {
        $title = $request->title;
        $CardId = $request->card_id;
        $tag = "";
        if (isset($request->tag)) {
            $tag = $request->tag;
        }
        $description = "";
        if (isset($request->description)) {
            $description = $request->description;
        }
        $user = $request->UserData->username;

        if (!Card::find($CardId)) {
            return response()->json(['status' => false, 'error' => 'card serch not found'], 400);
        }
        #上傳圖片
        $now = Carbon::now();
        // dd($now);
        $path = "";
        if ($request->hasFile('image')) {
            $file = $request->image;
            $path = $file->storeAs('images', 'task/' . $now . ' ' . $title . ' ' . $user . '.jpeg');
        }
        // dd($path);
        // dd($title, $user, $description, $tag, $path, $CardId);
        $store = Task::create([
            'title' => $title, 'status' => false,
            'create_user' => $user, 'update_user' => $user,
            'description' => $description, 'tag' => $tag,
            'image' => $path,
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
            return response()->json(['status' => false, 'error' => 'title search not found'], 400);
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
        $title = $request->title;
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
            'title' => "$title", 'update_user' => $user,
            'title' => $title, 'status' => $status,
            'update_user' => $user,
            'description' => $description, 'tag' => $tag,
            // 'image' => $image, 
            'card_id' => $CardId,
        ]);
        return response()->json(['status' => true, 'task_data' => $update], 200);
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
            return response()->json(['status' => false, 'error' => 'task search not found'], 400);
        }
        // dd($delete->image);
        if (isset($delete->image)) {
            $image = $delete->image;
            Storage::delete($image);
        }
        $delete->delete();
        return response()->json(['status' => true], 200);
    }
    public function upload(Request $request)
    {
        // dd($request);
        if ($request->hasFile('image')) {
            $username = $request->UserData->username;
            $file = $request->image;
            $path = $file->storeAs('image', 'user/' . $username . '.jpeg');
            // dd($request->card_id);
            return response()->json(['status' => true,], 201);
        } else {
            return response()->json(['status' => false, 'error' => 'upload error'], 400);
        }
    }
}
