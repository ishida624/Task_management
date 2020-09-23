<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CardsRequest;
use App\Users;
use App\Card;
use App\Groups;
use Illuminate\Http\Resources\MergeValue;

class CardController extends Controller
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
            $card->ShowTasks;
        }
        return response()->json(['status' => true, 'user_data' => $userData], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CardsRequest $request)
    {
        $CardName = $request->card_name;
        $user = $request->userData->username;
        $UserId = $request->userData->id;
        $store = Card::create(['card_name' => $CardName, 'create_user' => $user, 'private' => true]);
        $CardId = $store->id;
        Groups::create(['users_id' => $UserId, 'card_id' => $CardId]);
        return response()->json(['status' => true, 'card_data' => $store], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $userData = $request->userData;
        $cards = $userData->ShowCards->find($id);
        if (isset($cards)) {
            $cards->ShowTasks;
        } else {
            return response()->json(['status' => false, 'error' => 'card search not found'], 400);
        }
        return response()->json(['status' => true, 'card_data' => $cards], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CardsRequest $request, $id)
    {
        $userData = $request->userData;
        $CardName = $request->card_name;
        $cards = $userData->ShowCards->find($id);
        if (isset($cards)) {
            $cards->ShowTasks;
        } else {
            return response()->json(['status' => false, 'error' => 'card search not found'], 400);
        }
        $cards->update(['card_name' => $CardName]);
        return response()->json(['status' => true, 'card_data' => $cards], 200);
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
        $cards = $userData->ShowCards->find($id);
        if (isset($cards)) {
            $cards->ShowTasks;
        } else {
            return response()->json(['status' => false, 'error' => 'card search not found'], 400);
        }
        // return $cards;
        $cards->delete();
        return response()->json(['status' => true,], 200);
    }
}
