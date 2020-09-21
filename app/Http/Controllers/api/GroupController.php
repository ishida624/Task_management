<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Groups;
use App\Card;
use App\Users;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $userData = $request->userData;
        $groups = $userData->ShowGroups;
        return $groups;
    }
    public function card_users($cardId)
    {
        $users = Card::find($cardId)->ShowUsers;
        return $users;
    }
    public function store(Request $request, $cardId)
    {
        $userData = $request->userData;
        $card = $userData->ShowCards->find($cardId);
        if (!$card) {
            return response()->json(['status' => false, 'error' => 'card search not found'], 400);
        }
        // $cardOwner = $card->create_user;
        $userId = $request->user_id;
        $group = Groups::create(['users_id' => $userId, 'card_id' => $cardId]);
        return response()->json(['status' => true, 'group_data' => $group]);
    }

    public function delete_user(Request $request, $cardId)
    {
        $userData = $request->userData;
        $user = $userData->username;
        $deleteUserId = $request->user_id;
        // $card = Card::find($cardId)->ShowGroups;
        $card = Groups::where('card_id', $cardId)->get();

        if (!$card) {
            return response()->json(['status' => false, 'error' => 'card search not found'], 400);
        }
        $cardOwner = $userData->ShowCards->find($cardId)->create_user;
        # 先判斷使用者是否為卡片創立者
        if ($user  == $cardOwner) {
            $Lv = 1;
        } else {
            $Lv = 2;
        }

        // dd($Lv);
        $delete = $card->where('users_id', $deleteUserId)->first();
        $deleteUser = Users::find($deleteUserId)->username;
        if ($deleteUser == $cardOwner && $Lv == 2) {
            return response()->json(['status' => false, 'error' => 'you can not delete card owner'], 400);
        }
        if (!$deleteUser) {
            return response()->json(['status' => false, 'error' => 'user search not found'], 400);
        }
        // dd($deleteUser);
        $delete->delete();
        return response()->json(['status' => true,]);
    }
}
