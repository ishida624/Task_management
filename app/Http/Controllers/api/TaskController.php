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
    public function index(Request $request)
    {
        $userData = $request->userData;
        $cards = $userData->ShowCards;
        foreach ($cards as $card) {
            $task = $card->ShowTasks;
            foreach ($task as $value) {
                $tasks[] = $value;
            }
        }
        if (isset($tasks) != true) {
            $tasks = [];
        }
        return response()->json(['status' => true, 'task_data' => $tasks], 200);
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
        $CardId = (int)$request->card_id;

        // dd($CardId);
        $tag = $request->tag;
        if (!$tag) {
            $tag = "";
        }
        $description = $request->description;
        if (!$description) {
            $description = "";
        }

        $userData = $request->userData;
        $user = $userData->username;
        $cards = $userData->ShowCards;
        $card = $cards->find($CardId);
        if (!$card) {
            return response()->json(['status' => false, 'error' => 'card search not found'], 400);
        }
        // dd($card);
        // if (!Card::find($CardId)) {
        //     return response()->json(['status' => false, 'error' => 'card search not found'], 400);
        // }
        $store = Task::create([
            'title' => $title, 'status' => false,
            'create_user' => $user, 'update_user' => $user,
            'description' => $description, 'tag' => $tag,
            'card_id' => $CardId,
        ]);
        #上傳圖片
        // $now = date('Y-m-d_H:i:s');
        if ($request->hasFile('image')) {
            // $file = $request->image;
            // $path = $file->storeAs('images', 'task/' . $now . '_task' . $store->id . '.jpeg');
            // $store->update(['image' => $path]);

            #GCS
            $taskId = $store->id;
            $disk = Storage::disk('gcs');
            $image = $request->image;
            // dd($image);
            // $disk->delete($userData->image);
            $disk->put("image/task/task$taskId", $image);
            $path = $disk->files("image/task/task$taskId");
            $store->update(['image' => $path[0]]);
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
            return response()->json(['status' => false, 'error' => 'task search not found'], 400);
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
        $userData = $request->userData;
        $user = $userData->username;
        $cards = $userData->ShowCards;
        #找出每張card的task,符合id的話就跳出回圈
        foreach ($cards as  $card) {
            $task = $card->ShowTasks->find($id);
            if (isset($task)) {
                break;
            }
        }
        if (!$task) {
            return response()->json(['status' => false, 'error' => 'task search not found'], 400);
        }

        $title = $task->title;
        $tag = $task->tag;
        $status = $task->status;
        $cardId = $task->card_id;
        $description = $task->description;
        $path = $task->image;
        if ($request->delete_image == true) {
            $disk = Storage::disk('gcs');
            $disk->delete($path);
            $task->update(['image' => '']);
        }
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
            $cardId = (int)$request->card_id;
        }
        if (isset($request->description)) {
            $description = $request->description;
        }
        if (!Card::find($cardId)) {
            return response()->json(['status' => false, 'error' => 'card search not found'], 400);
        }
        #更新圖片
        // $now = Carbon::now();
        $now = date('Y-m-d_H:i:s');
        if ($request->hasFile('image')) {
            #刪除原本的圖片
            // Storage::delete($path);
            // $file = $request->image;
            // $path = $file->storeAs('images', 'task/' . $now . '_task' . $id . '.jpeg');

            #GCS
            $taskId = $task->id;
            $disk = Storage::disk('gcs');
            $image = $request->image;
            $disk->delete($path);
            $disk->put("image/task/task$taskId", $image);
            $path = $disk->files("image/task/task$taskId");
            // dd($path);
            $task->update(['image' => $path[0]]);
        }
        $task->update([
            'title' => "$title", 'update_user' => $user,
            'title' => $title, 'status' => $status,
            'update_user' => $user,
            'description' => $description, 'tag' => $tag,
            // 'image' => $path,
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
    public function destroy(Request $request, $id)
    {
        $userData = $request->userData;
        $cards = $userData->ShowCards;
        #找出每張card的task,符合id的話就跳出回圈
        foreach ($cards as  $card) {
            $task = $card->ShowTasks->find($id);
            if (isset($task)) {
                break;
            }
        }
        if (!$task) {
            return response()->json(['status' => false, 'error' => 'task search not found'], 400);
        }
        # 刪除圖片
        if (isset($task->image)) {
            $image = $task->image;
            // Storage::delete($image);

            # GCS
            $disk = Storage::disk('gcs');
            $disk->delete($image);
        }
        $task->delete();
        return response()->json(['status' => true], 200);
    }
}
