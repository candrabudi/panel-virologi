<?php

namespace App\Http\Controllers;

use App\Models\AiKnowledgeBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiKnowledgeBaseController extends Controller
{
    public function index(Request $request)
    {
        $query = AiKnowledgeBase::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('topic', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        $knowledge = $query->latest()->paginate(10);
        
        // Stats for cards
        $totalItems = AiKnowledgeBase::count();
        $totalCategories = AiKnowledgeBase::distinct('category')->count();
        $totalUsage = AiKnowledgeBase::sum('usage_count');
        $avgRelevance = AiKnowledgeBase::avg('relevance_score');

        return view('ai.knowledge.index', compact('knowledge', 'totalItems', 'totalCategories', 'totalUsage', 'avgRelevance'));
    }

    public function create()
    {
        return view('ai.knowledge.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:50',
            'topic' => 'required|string|max:200',
            'content' => 'required|string',
            'context' => 'nullable|string',
            'examples' => 'nullable|string',
            'references' => 'nullable|string',
            'tags' => 'nullable|string',
            'source' => 'nullable|string|max:50',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['relevance_score'] = 0; // Default
        $validated['usage_count'] = 0; // Default

        // Ensure JSON fields are stored as clean JSON, not HTML entities
        if (isset($validated['tags'])) $validated['tags'] = html_entity_decode($validated['tags']);
        if (isset($validated['references'])) $validated['references'] = html_entity_decode($validated['references']);

        AiKnowledgeBase::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Knowledge item created successfully.',
                'redirect' => route('ai.knowledge.index')
            ]);
        }

        return redirect()->route('ai.knowledge.index')
            ->with('success', 'Knowledge item created successfully.');
    }

    public function edit($id)
    {
        $knowledge = AiKnowledgeBase::findOrFail($id);
        return view('ai.knowledge.edit', compact('knowledge'));
    }

    public function update(Request $request, $id)
    {
        $knowledge = AiKnowledgeBase::findOrFail($id);

        $validated = $request->validate([
            'category' => 'required|string|max:50',
            'topic' => 'required|string|max:200',
            'content' => 'required|string',
            'context' => 'nullable|string',
            'examples' => 'nullable|string',
            'references' => 'nullable|string',
            'tags' => 'nullable|string',
            'source' => 'nullable|string|max:50',
            'relevance_score' => 'nullable|numeric|min:0|max:100',
        ]);

        // Ensure JSON fields are stored as clean JSON, not HTML entities
        if (isset($validated['tags'])) $validated['tags'] = html_entity_decode($validated['tags']);
        if (isset($validated['references'])) $validated['references'] = html_entity_decode($validated['references']);

        $knowledge->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Knowledge item updated successfully.',
                'redirect' => route('ai.knowledge.index')
            ]);
        }

        return redirect()->route('ai.knowledge.index')
            ->with('success', 'Knowledge item updated successfully.');
    }

    public function destroy($id)
    {
        $knowledge = AiKnowledgeBase::findOrFail($id);
        $knowledge->delete();

        return redirect()->route('ai.knowledge.index')
            ->with('success', 'Knowledge item deleted successfully.');
    }
}
