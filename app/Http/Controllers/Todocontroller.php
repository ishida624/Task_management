<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\T1;
use Illuminate\Support\Str;
use App\Http\Requests\TodosRequest;

class Todocontroller extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('Auth')->except('readTable');
    // }
    public function readTable()
    {
        $data = T1::all();
        return  view('todo_index', ['data' => $data]);
    }

    public function update(TodosRequest $update)
    {
        // $userToken = $update->cookie('userToken');
        // $user = Admin::where('remember_token', "$userToken")->first('admin')->admin;
        $user = Auth::user()->name;
        $id = $update->input('no');
        $item = $update->input('item');
        T1::find($id)->update(['item' => "$item", 'update_user' => 'admin', 'update_user' => $user]);
        //return redirect()->back()->with('message', 'please login');
        //return 'please login';
        return redirect('todolist');
        // return $update;
    }
    public function delete(Request $delete)
    {
        $id = $delete->input('id');
        T1::find($id)->delete();
        return redirect('todolist');
        // return $delete;
    }
    public function create(TodosRequest $create)
    {
        // $userToken = $create->cookie('userToken');
        // $user = Admin::where('remember_token', "$userToken")->first('admin')->admin;
        $user = Auth::user()->name;
        $item = $create->input('item');
        // $item = $create->validate(['item' => 'required']) ;
        // $rules=[
        //             'item' => 'required|max:255',
        //         ];
        // $messages = [
        //     'item.required' => 'item can not null.' ,
        //     'item.max' => 'item can not over 255 characters'
        // ];
        // $validator = Validator::make($create->all(), $rules, $messages);
        // if ($validator->fails()) {
        //     // dd($validator);
        //     return redirect('todolist')
        //                         ->withErrors($validator)
        //                         ->withInput();
        // }
        T1::create(['item' => "$item", 'status' => '未完成', 'update_user' => 'admin', 'update_user' => $user]);
        return redirect('todolist');
    }
    public function complete(Request $complete)
    {
        // dd('Hello');
        // $userToken = $complete->cookie('userToken');
        // $user = Admin::where('remember_token', "$userToken")->first('admin')->admin;
        // dd($user->admin);
        $user = Auth::user()->name;
        $id = $complete->input('id');
        $data = T1::find($id);
        $status = $data->status;
        if ($status == '未完成') {
            $data->update(['status' => '已完成', 'update_user' => $user]);
        } else {
            $data->update(['status' => '未完成', 'update_user' => $user]);
        }
        return redirect('todolist');

        // $value = $complete->cookie('userToken');
        // return $value;
    }
}
