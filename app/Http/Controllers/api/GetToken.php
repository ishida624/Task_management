<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Users;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\UserRequest;
use Laravel\Socialite\Facades\Socialite;
use Google\Client;
use Google_Service_Oauth2;


class GetToken extends Controller
{
    public function register(UserRequest $request)
    {
        $username = $request->username;
        $password = $request->password;
        $email = $request->email;
        # validation check username and password formt
        // $rules = [
        //     'username' => 'required|max:16|alpha_dash',
        //     'password' => 'required|regex:/[0-9a-zA-Z]{8}/',
        //     'email' => 'required|email',
        // ];
        // $messages = [
        //     'username.required' => 'username can not null. ',
        //     'username.max' => 'username can not over 16 characters. ',
        //     'username.alpha_dash' => 'username only have alpha-numeric characters, as well as dashes and underscores . ',
        //     'password.regex' => 'password should over 8 characters and only 0-9,a-z,A-Z. ',
        //     'password.required' => 'password should over 8 characters and only 0-9,a-z,A-Z. ',
        //     'email.email' => 'The email must be a valid email address. ',
        // ];
        // $validator = Validator::make($request->all(), $rules, $messages);
        // if ($validator->fails()) {
        //     $error = $validator->errors()->first();
        //     return response()->json(['status' => false, 'error' => $error], 400);
        // }

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
    public function login(Request $request)
    {
        $password = $request->password;
        $email = $request->email;
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

            $dbUser->update(['remember_token' => $token]);
            return response()->json(['status' => true, 'login_data' => ['userToken' => $token]], 200);
        } else {
            return response()->json(['status' => false, 'error' => 'email or password false'], 400);
        }
    }
    public function mail($email)
    {
        $user = Users::where('email', $email)->first();
        if (!$user) {
            return response()->json(['status' => false, 'error' => 'email false'], 400);
        }
        $userToken = $user->remember_token;
        $url = "http://127.0.0.1:8003/api/password/change/$userToken";
        $text = '點此修改密碼為 a00000000  ' . $url;
        Mail::raw($text, function ($message) use ($email) {
            $message->to($email)->subject('hiyaa');
        });
        return response()->json(['status' => true,], 200);
    }
    public function change_password($userToken)
    {
        $user = Users::where('remember_token', $userToken)->first();
        if (!$user) {
            return response()->json(['status' => false, 'error' => 'token false'], 400);
        }
        $hash = password_hash('a00000000', PASSWORD_DEFAULT);
        $user->update(['password' => $hash]);
        return response()->json(['status' => true, 'message' => 'password had be changed'], 200);
    }
    // public function googleOauthCode()
    // {
    //     return Socialite::driver('google')->redirect();
    // }
    public function googleOauthLogin(Request $request)
    {
        $client = new Client;
        // $idToken = $client->auth->idToken;
        $idToken = $request->idToken;

        ## 解開idToken若不是jwt則回傳400
        $resource = $client->verifyIdToken($idToken);
        if ($resource == false) {
            return response()->json(['status' => false, 'message' => 'idToken is invalid'], 400);
        }

        ## db中尋找有沒有註冊過
        $dbUser = Users::where('email', $resource['email'])->first();
        ## 生產亂數token
        $User = new Users;
        $token = $User->GetToken();

        ## 若db中有找到user，直接更新token
        if (isset($dbUser)) {
            $dbUser->update(['remember_token' => $token]);
        }

        # 若db中沒有登入過就直接註冊
        if (!$dbUser) {
            $dbUser = Users::create([
                'username' => $resource['name'],
                'remember_token' => $token,
                'email' => $resource['email'],
                'oauth' => true,
                'image' => $resource['picture'],
            ]);
            // dd($dbUser);
        }
        #使用oauth登入時，email帳號已存在回復400
        if ($dbUser->oauth != true) {
            return response()->json(['status' => false, 'message' => 'This email is already exists'], 400);
        }
        return response()->json(['status' => true, 'login_data' => ['userToken' => $token]], 200);
    }
}
