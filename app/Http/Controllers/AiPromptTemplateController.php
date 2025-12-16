<?php

namespace App\Http\Controllers;

use App\Models\AiPromptTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AiPromptTemplateController extends Controller
{
    public function index()
    {
        return view('ai.prompts.index', [
            'prompts' => AiPromptTemplate::orderBy('type')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:system,context,fallback',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        AiPromptTemplate::create([
            'type' => $request->type,
            'content' => $request->content,
            'is_active' => true,
        ]);

        return response()->json([
            'status' => true,
        ]);
    }

    public function update(Request $request, $id)
    {
        $prompt = AiPromptTemplate::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $prompt->update([
            'content' => $request->content,
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'status' => true,
        ]);
    }
}
