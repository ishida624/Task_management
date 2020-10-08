<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Groups;
use App\Card;
use App\Users;

class GroupController extends Controller
{
    #顯示使用者所有的Groups
    public function index(Request $request)
    {
        $userData = $request->userData;
        $groups = $userData->ShowGroups;
        return response()->json(['status' => true, 'groups_data' => $groups]);
    }

    #顯示card 所有的使用者
    public function card_users($cardId)
    {
        $card = Card::find($cardId);
        if (!$card) {
            return response()->json(['status' => false, 'error' => 'card search not found'], 400);
        }
        $users = $card->ShowUsers;
        return response()->json(['status' => true, 'users_data' => $users]);
    }

    #新增user 進來群組
    public function store(Request $request, $cardId)
    {
        $userData = $request->userData;
        $card = $userData->ShowCards->find($cardId);
        if (!$card) {
            return response()->json(['status' => false, 'error' => 'card search not found'], 400);
        }
        // $cardOwner = $card->create_user;
        $userEmail = $request->email;
        $addUser = Users::where('email', $userEmail)->first();
        // dd($addUser);
        if (!$addUser) {
            return response()->json(['status' => false, 'error' => 'user search not found'], 400);
        }
        $userId = $addUser->id;
        $group = $card->ShowGroups->where('users_id', $userId)->first();
        if (isset($group)) {
            return response()->json(['status' => false, 'error' => 'user is already in card'], 400);
        }

        $create = Groups::create(['users_id' => $userId, 'card_id' => $cardId,]);
        $card->update(['private' => false]);
        return response()->json(['status' => true, 'group_data' => $create]);
    }

    #從卡片中刪除使用者
    public function delete_user(Request $request, $cardId)
    {
        $userData = $request->userData;
        $user = $userData->username;
        $deleteUserId = $request->user_id;
        $card = $userData->ShowCards->find($cardId);
        if (!$card) {
            return response()->json(['status' => false, 'error' => 'card search not found'], 400);
        }
        $cardOwner = $card->create_user;
        $cardGroup = $card->ShowGroups;
        # 先判斷使用者是否為卡片創立者
        if ($user  == $cardOwner) {
            $Lv = 1;
        } else {
            $Lv = 2;
        }

        $delete = $cardGroup->where('users_id', $deleteUserId)->first();
        if (!$delete) {
            return response()->json(['status' => false, 'error' => 'user search not found'], 400);
        }
        $deleteUser = Users::find($deleteUserId)->username;
        if ($deleteUser == $cardOwner && $Lv == 2) {
            return response()->json(['status' => false, 'error' => 'you can not delete card owner'], 400);
        }
        $delete->delete();
        #刪除後若card擁有者不見了，替換下一位card擁有者
        $card = Card::find($cardId);
        $group = $card->ShowGroups;
        if ($deleteUser == $cardOwner) {
            if (isset($group[0])) {
                $nextOwner = $group->first()->ShowUsers->username;
                $card->update(['create_user' => $nextOwner]);
            } else {
                $card->delete();
            }
        }
        if ($card->ShowUsers->count() == 1) {
            $card->update(['private' => true]);
        }
        return response()->json(['status' => true,]);
    }
}
