<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Google\Cloud\Storage\StorageClient;

class UserController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($email)
    {
        // $email = $request->email;
        $userData = Users::where('email', $email)->first();
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
        $rules = [
            'username' => 'max:16|alpha_dash',
            'password' => 'regex:/[0-9a-zA-Z]{8}/',
        ];
        $messages = [
            'username.max' => 'username can not over 16 characters. ',
            'username.alpha_dash' => 'username only have alpha-numeric characters, as well as dashes and underscores . ',
            'password.regex' => 'password should over 8 characters and only 0-9,a-z,A-Z. ',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['status' => false, 'error' => $error], 400);
        }
        $userData = $request->userData;
        $username = $userData->username;
        $hash = $userData->password;

        if (isset($request->username)) {
            $username = $request->username;
            $User = Users::where('username', $username)->first();
            if (isset($User)) {
                return response()->json(['status' => false, 'error' => 'This account already exists'], 400);
            }
            $cards = $userData->ShowCards;
            foreach ($cards as $card) {
                $task = $card->ShowTasks;
                $card->update(['create_user' => $username]);
                foreach ($task as $value) {
                    $value->update(['create_user' => $username]);
                }
            }
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
        $email = $userData->email;

        #上傳圖片
        // $now = date('Y-m-d_H:i:s');
        if ($request->hasFile('image')) {
            $image = $request->image;
            // Storage::delete($userData->image);
            // $path = $image->storeAs('images', 'user/' . $now . '_' . $username . '.jpeg');
            // $userData->update(['image' => $path]);
            // dd(__DIR__);
            # GCS
            $disk = Storage::disk('gcs');
            $disk->delete($userData->image);
            $disk->put("image/user/$email", $image);
            $path = $disk->files("image/user/$email");
            $userData->update(['image' => $path[0]]);
            // dd($path);
        } else {
            return response()->json(['status' => false, 'error' => 'upload error'], 400);
        }
        return response()->json(['status' => true,], 201);
    }
    public function deleteImage(Request $request)
    {
        $userData = $request->userData;
        // $path = $userData->image;
        // Storage::delete($path);
        $disk = Storage::disk('gcs');
        $disk->delete($userData->image);
        $path = "";
        $userData->update(['image' => $path]);
        return response()->json(['status' => true,], 200);
    }
}
