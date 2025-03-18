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
            'discord' => 'nullable|string|max:255',
        ]);

        // Check if the IGN already exists
        $existingPlayer = Player::where('ign', $request->ign)->first();
        if ($existingPlayer) {
            return response()->json(['error' => 'IGN already exists.'], 400);
        }

        $accessCode = Player::generateMemberAccessCode();

        $playerData = $request->only(['ign', 'level', 'class', 'power', 'discord']);
        $playerData['member_access_code'] = $accessCode;

        $player = Player::create($playerData);
        return response()->json($player, 201);
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
        $validated = $request->validate([
            'access_code' => 'required|string',
            'ign' => 'sometimes|max:255',
            'level' => 'integer',
            'class' => 'string|max:255',
            'power' => 'integer',
            'guild' => 'string|max:255',
            'status' => 'string|max:50',
            'power_screenshot' => 'nullable|image|max:2048',
            'discord' => 'string|max:255',
        ]);

        $playerData = $request->only(['ign', 'level', 'class', 'power', 'guild', 'status', 'discord']);

        // Verify access code again for security
        if ($player->member_access_code !== $validated['access_code']) {
            return redirect()->back()
                ->withErrors(['access_code' => 'Invalid access code.'])
                ->withInput();
        }

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

    /**
     * Verify access code and show edit form
     */
    public function verifyAndEdit(Request $request)
    {
        $validated = $request->validate([
            'ign' => 'required|string|exists:players,ign',
            'access_code' => 'required|string',
        ]);

        // Find the player by IGN
        $player = Player::where('ign', $validated['ign'])->first();

        // Verify the access code
        if (!$player || $player->member_access_code !== $validated['access_code']) {
            return redirect()->back()
                ->withErrors(['access_code' => 'Invalid access code for this IGN.'])
                ->withInput();
        }

        // Show the edit form with the player data
        return view('players.edit', ['player' => $player]);
    }
}
