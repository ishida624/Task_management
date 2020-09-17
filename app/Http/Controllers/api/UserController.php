<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $index = User::all();
        return $index;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
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
        $UserData = $request->UserData;
        $username = $UserData->username;
        $password = $UserData->password;
        if (isset($request->username)) {
            $username = $request->username;
        }
        if (isset($request->password)) {
            $password = $request->password;
            $hash = password_hash($password, PASSWORD_DEFAULT);
        }
        $UserData->update(['username' => $username, 'password' => $hash]);
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
}
