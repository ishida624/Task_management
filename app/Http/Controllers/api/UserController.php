<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userData = $request->userData;
        return response()->json(['status' => true, 'user_data' => $userData]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userData = Users::find($id);
        return response()->json(['status' => true, 'user_data' => $userData]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_user_data(Request $request)
    {
        // dd('hello');
        $rules = [
            'username' => 'max:16',
            'password' => 'regex:/[0-9a-zA-Z]{8}/',
        ];
        $messages = [
            'username.max' => 'username can not over 16 characters. ',
            'password.regex' => 'password should over 8 characters and only 0-9,a-z,A-Z. ',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => false, 'error' => $error], 400);
        }
        $userData = $request->userData;
        $username = $userData->username;
        $password = $userData->password;
        if (isset($request->username)) {
            $username = $request->username;
        }
        if (isset($request->password)) {
            $password = $request->password;
            $hash = password_hash($password, PASSWORD_DEFAULT);
        }

        $userData->update(['username' => $username, 'password' => $hash,]);
        return response()->json(['status' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function upload(Request $request)
    {
        $userData = $request->userData;
        $username = $userData->username;

        #上傳圖片
        $now = Carbon::now();
        if ($request->hasFile('image')) {
            $image = $request->image;
            Storage::delete($userData->image);
            $path = $image->storeAs('images', 'user/' . $now . '_' . $username . '.jpeg');
            $userData->update(['image' => $path]);
        } else {
            return response()->json(['status' => false, 'error' => 'upload error'], 400);
        }
        return response()->json(['status' => true,], 201);
    }
    public function deleteImage(Request $request)
    {
        $userData = $request->userData;
        $path = $userData->image;
        Storage::delete($path);
        $path = "";
        $userData->update(['image' => $path]);
        return response()->json(['status' => true,], 200);
    }
}
