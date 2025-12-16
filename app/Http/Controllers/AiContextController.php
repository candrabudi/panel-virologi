<?php

namespace App\Http\Controllers;

use App\Models\AiContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AiContextController extends Controller
{
    public function index()
    {
        return view('ai.contexts.index', [
            'contexts' => AiContext::orderBy('id')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:ai_contexts,code',
            'name' => 'required|string',
            'use_internal_source' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        AiContext::create([
            'code' => $request->code,
            'name' => $request->name,
            'use_internal_source' => $request->use_internal_source,
            'is_active' => true,
        ]);

        return response()->json([
            'status' => true,
        ]);
    }

    public function update(Request $request, $id)
    {
        $context = AiContext::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'use_internal_source' => 'required|boolean',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $context->update([
            'name' => $request->name,
            'use_internal_source' => $request->use_internal_source,
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'status' => true,
        ]);
    }
}
