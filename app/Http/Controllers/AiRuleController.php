<?php

namespace App\Http\Controllers;

use App\Models\AiContext;
use App\Models\AiRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AiRuleController extends Controller
{
    public function index()
    {
        return view('ai.rules.index', [
            'rules' => AiRule::with('context')->orderByDesc('id')->get(),
            'contexts' => AiContext::where('is_active', 1)->orderBy('code')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:allow_keyword,block_topic,regex,source_policy',
            'value' => 'required|string|max:500',
            'ai_context_id' => 'nullable|exists:ai_contexts,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        AiRule::create([
            'type' => $request->type,
            'value' => $request->value,
            'ai_context_id' => $request->ai_context_id,
            'category' => 'cybersecurity',
            'is_active' => true,
            'note' => $request->note,
        ]);

        return response()->json([
            'status' => true,
        ]);
    }

    public function update(Request $request, $id)
    {
        $rule = AiRule::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'value' => 'required|string|max:500',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $rule->update([
            'value' => $request->value,
            'is_active' => $request->is_active,
            'note' => $request->note,
        ]);

        return response()->json([
            'status' => true,
        ]);
    }

    public function destroy($id)
    {
        AiRule::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
        ]);
    }
}
