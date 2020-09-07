<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Users;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class GetToken extends Controller
{
    public function register(Request $register)
    {
        $username = $register->username;
        $password = $register->password;
        $email = $register->email;
        // $checkpassword = $register->checkpassword;
        # validation check username and password formt
        $rules = [
            'username' => 'required|max:16',
            'password' => 'required|regex:/[0-9a-zA-Z]{8}/',
            'email' => 'required|email',
        ];
        $messages = [
            'username.required' => 'username can not null. ',
            'username.max' => 'username can not over 16 characters. ',
            'password.regex' => 'password should over 8 characters and only 0-9,a-z,A-Z. ',
            'password.required' => 'password should over 8 characters and only 0-9,a-z,A-Z. ',
            'email.email' => 'The email must be a valid email address. ',
        ];
        $validator = Validator::make($register->all(), $rules, $messages);
        if ($validator->fails()) {
            $error = $validator->errors()->getMessages();
            $reason1 = "";
            $reason2 = "";
            $reason3 = "";
            if (isset($error['username'][0])) {
                $reason1 = $error['username'][0];
            }
            if (isset($error['password'][0])) {
                $reason2 =  $error['password'][0];
            }
            if (isset($error['email'][0])) {
                $reason3 =  $error['email'][0];
            }
            return response()->json(['status' => false, 'error' => $reason1 . $reason2 . $reason3], 400);
        }

        #尋找是否有相同帳號名
        $User = Users::where('username', $username)->first();
        if (isset($User)) {
            return response()->json(['status' => false, 'error' => 'This account already exists'], 400);
        }
        #尋找是否有相同email
        $SameEmail = Users::where('email', $email)->first();
        if (isset($SameEmail)) {
            return response()->json(['status' => false, 'error' => 'This email already exists'], 400);
        }

        #密碼加密後加入資料庫
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $User = Users::create([
            'username' => $username, 'password' => $hash,
            'remember_token' => 'new user',
            'email' => $email,
        ]);
        return response()->json(['status' => true], 201);
    }
    public function login(Request $login)
    {
        $password = $login->password;
        // $username = $login->username;
        $email = $login->email;
        $dbUser = Users::where('email', $email)->first();

        #判斷帳號是否存在
        if (!$dbUser) {
            return response()->json(['status' => false, 'error' => 'email or password false'], 400);
        }
        $dbPassword = $dbUser->password;

        #判斷密碼是否正確、給亂數token
        if (password_verify($password, $dbPassword)) {
            #避免token重複
            do {
                $token = Str::random(15);
                $tokenCheck = Users::where('remember_token', $token)->first();
                if (isset($tokenCheck)) {
                    $sameToken = true;
                } else {
                    $sameToken = false;
                }
            } while ($sameToken);

            Users::where('email', $email)->update(['remember_token' => $token]);
            return response()->json(['status' => true], 200)->header('userToken', $token);
        } else {
            return response()->json(['status' => false, 'error' => 'email or password false'], 400);
        }
    }
}
