<?php

namespace App\Http\Controllers;

use App\Models\AiContext;
use App\Models\AiPromptBinding;
use App\Models\AiPromptTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AiPromptBindingController extends Controller
{
    public function index()
    {
        return view('ai.bindings.index', [
            'contexts' => AiContext::where('is_active', 1)->orderBy('code')->get(),
            'prompts' => AiPromptTemplate::where('is_active', 1)->orderBy('type')->get(),
            'bindings' => AiPromptBinding::with(['context', 'prompt'])->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ai_context_id' => 'required|exists:ai_contexts,id',
            'ai_prompt_template_id' => 'required|exists:ai_prompt_templates,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        AiPromptBinding::firstOrCreate([
            'ai_context_id' => $request->ai_context_id,
            'ai_prompt_template_id' => $request->ai_prompt_template_id,
        ], [
            'is_active' => true,
        ]);

        return response()->json([
            'status' => true,
        ]);
    }

    public function destroy($id)
    {
        AiPromptBinding::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
        ]);
    }
}
