<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Player::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ign' => 'required|string|max:255',
            'level' => 'nullable|integer',
            'class' => 'nullable|string|max:50',
            'power_screenshot' => 'nullable|image|max:2048',
            'power' => 'nullable|integer',
        ]); 

        $playerData = $request->only(['ign', 'level', 'class', 'power']);

        $player = Player::create($playerData);
        return $player;
    }

    /**
     * Display the specified resource.
     */
    public function show(Player $player)
    {
        return $player;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Player $player)
    {
        $request->validate([
            'ign' => 'sometimes|max:255',
            'level' => 'integer',
            'class' => 'string|max:255',
            'power' => 'integer',
            'guild' => 'string|max:255',
            'status' => 'string|max:50',
            'power_screenshot' => 'nullable|image|max:2048',
        ]);

        $playerData = $request->only(['ign', 'level', 'class', 'power', 'guild', 'status']);

        $player->update($playerData);
        return $player;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player)
    {
        $player->delete();
        return response()->noContent();
    }
}
