<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource
     */
    public function index(Request $request)
    {
        $query = Player::query();

        // Guild filter (dropdown)
        if ($request->has('guild')) {
            $query->where('guild', $request->guild);
        }

        // IGN search (search box)
        if ($request->has('search')) {
            $query->where('ign', 'like', '%' . $request->search . '%');
        }

        // Class filter (buttons)
        if ($request->has('class')) {
            $query->where('class', $request->class);
        }

        // Apply sorting (from column headers)
        $sortable = ['status', 'level', 'growth_rate'];
        $sortBy = in_array($request->sort_by, $sortable) ? $request->sort_by : 'id';
        $sortOrder = in_array($request->sort_order, ['asc', 'desc']) ? $request->sort_order : 'asc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'ign' => 'required|string|max:255',
                'level' => 'required|integer', // Marking level as required
                'class' => 'required|string|max:50', // Marking class as required
                'power_screenshot' => 'nullable|image|max:2048',
                'power' => 'required|integer', // Marking power as required
                'discord' => 'required|string|max:255', // Marking discord as required
            ], [
                'ign.required' => 'IGN is required.',
                'level.required' => 'Level is required.',
                'level.integer' => 'Level must be an integer.',
                'class.required' => 'Class is required.',
                'class.string' => 'Class must be a string.',
                'power.required' => 'Power is required.',
                'power.integer' => 'Power must be an integer.',
                'discord.required' => 'Discord is required.',
                'discord.string' => 'Discord must be a string.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        // Check if the IGN already exists
        $existingPlayer = Player::where('ign', $request->ign)->first();
        if ($existingPlayer) {
            return response()->json(['errors' => ['ign' => ['IGN already exists.']]], 422);
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
    public function verifyPlayer(Request $request)
    {
        try {
            $validated = $request->validate([
                'member_access_code' => 'required|string',
            ], [
                'member_access_code.required' => 'Access code is required.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        // Find the player by access code
        $player = Player::where('member_access_code', $validated['member_access_code'])->first();

        // Check if player exists
        if (!$player) {
            return response()->json(['error' => 'Access code doesn\'t match any record.'], 400);
        }

        return response()->json($player);
    }

    public function playerUpdate(Request $request, Player $player)
    {
        $validated = $request->validate([
            'member_access_code' => 'required|string',
            'ign' => 'sometimes|max:255',
            'level' => 'integer',
            'class' => 'string|max:255',
            'power' => 'integer',
            'power_screenshot' => 'nullable|image|max:2048',
            'discord' => 'string|max:255',
        ]);

        // Verify access code again for security
        if ($player->member_access_code !== $validated['member_access_code']) {
            return response()->json([
                'message' => 'Invalid access code.',
                'errors' => ['member_access_code' => 'Invalid access code.']
            ], 403);
        }

        $playerData = $request->only(['ign', 'level', 'class', 'power', 'discord']);

        $player->update($playerData);
        return response()->json($player);
    }

    /**
     * Get distinct class counts for a specific guild.
     */
    public function distinctClassPerGuild($guildName)
    {
        // Fetch the guild ID based on the guild's name
        $guild = DB::table('guilds')->where('name', $guildName)->first();

        if (!$guild) {
            return response()->json(['error' => 'Guild not found.'], 404);
        }

        // Use the guild ID to fetch class counts
        $classCounts = DB::table('players')
            ->select('class', DB::raw('COUNT(*) as total_count'))
            ->where('guild', $guild->name)
            ->groupBy('class')
            ->get();

        return response()->json($classCounts);
    }
}
