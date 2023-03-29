<?php

namespace App\Http\Controllers;

use App\Models\Preset;
use Illuminate\Http\Request;


class PresetController extends Controller
{
    public function store(Request $request)
    {
        $preset = new Preset();
        $preset->fill([
            'temperature' => $request->temperature,
            'frequency_penalty' => $request->frequency_penalty,
            'presence_penalty' => $request->presence_penalty,
            'max_tokens' => $request->max_tokens,
            'top_p' => $request->top_p,
        ])->save();

        return redirect()->back();
    }

    public function loadPresets()
    {
        $presets = Preset::select(['id', "temperature", "frequency_penalty", "presence_penalty", "max_tokens", "top_p"])->get()->toArray();
        return response()->json($presets, 200);
    }
}
