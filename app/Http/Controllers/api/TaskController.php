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
        if (!$title) {
            $title = 'new task';
        }
        $CardId = $request->card_id;
        $tag = $request->tag;
        if (!$tag) {
            $tag = "";
        }
        $description = $request->description;
        if (!$description) {
            $description = "";
        }
        $user = $request->UserData->username;
        // dd($title);
        if (!Card::find($CardId)) {
            return response()->json(['status' => false, 'error' => 'card serch not found'], 400);
        }
        $store = Task::create([
            'title' => $title, 'status' => false,
            'create_user' => $user, 'update_user' => $user,
            'description' => $description, 'tag' => $tag,
            'card_id' => $CardId,
        ]);
        // dd($store->id);
        #上傳圖片
        $now = Carbon::now();
        if ($request->hasFile('image')) {
            $file = $request->image;
            $path = $file->storeAs('images', 'task/' . $now . ' task' . $store->id . '.jpeg');
            $store->update(['image' => $path]);
        }

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
        $user = $request->UserData->username;
        $task = Task::find($id);
        $title = $task->title;
        $tag = $task->tag;
        $status = $task->status;
        $cardId = $task->card_id;
        $description = $task->description;
        // dd($request->description);
        if (isset($request->title)) {
            $title = $request->title;
        }
        if (isset($request->tag)) {
            $tag = $request->tag;
        }
        if (isset($request->status)) {
            $status = $request->status;
        }
        if (isset($request->card_id)) {
            $cardId = $request->card_id;
        }
        if (isset($request->description)) {
            $description = $request->description;
        }
        if (!$task) {
            return response()->json(['status' => false, 'error' => 'task search not found'], 400);
        }
        if (!Card::find($cardId)) {
            return response()->json(['status' => false, 'error' => 'card search not found'], 400);
        }
        $task->update([
            'title' => "$title", 'update_user' => $user,
            'title' => $title, 'status' => $status,
            'update_user' => $user,
            'description' => $description, 'tag' => $tag,
            'card_id' => $cardId,
        ]);
        return response()->json(['status' => true, 'task_data' => $task], 200);
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
        # 刪除圖片
        if (isset($delete->image)) {
            $image = $delete->image;
            Storage::delete($image);
        }
        $delete->delete();
        return response()->json(['status' => true], 200);
    }
    public function upload(Request $request, $id)
    {
        #更新圖片
        $now = Carbon::now();
        if ($request->hasFile('image')) {
            $file = $request->image;
            $task = Task::find($id);
            $path = $file->storeAs('images', 'task/' . $now . ' task' . $id . '.jpeg');
            $task->update(['image' => $path]);
            #刪除原本的圖片
            Storage::delete($task->image);
            $file = $request->image;
            return response()->json(['status' => true,], 201);
        } else {
            return response()->json(['status' => false, 'error' => 'upload error'], 400);
        }
    }
}
