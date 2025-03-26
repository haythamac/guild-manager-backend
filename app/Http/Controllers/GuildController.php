<?php

namespace App\Http\Controllers;

use App\Models\Guild;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuildController extends Controller
{
    //

    public function index()
    {
        return Guild::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        
        return Guild::create($validated);
    }

}
