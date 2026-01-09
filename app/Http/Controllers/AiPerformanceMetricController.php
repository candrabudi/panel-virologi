<?php

namespace App\Http\Controllers;

use App\Models\AiPerformanceMetric;
use Illuminate\Http\Request;

class AiPerformanceMetricController extends Controller
{
    public function index()
    {
        $metrics = AiPerformanceMetric::orderBy('metric_date', 'desc')->paginate(10);

        // Calculate summary stats
        $totalQueries = AiPerformanceMetric::sum('total_queries');
        $avgResponseTime = AiPerformanceMetric::avg('average_response_time');
        $avgSatisfaction = AiPerformanceMetric::avg('user_satisfaction_score');
        $totalKnowledgeHits = AiPerformanceMetric::sum('knowledge_base_hits');

        return view('ai.performance.index', compact(
            'metrics',
            'totalQueries',
            'avgResponseTime',
            'avgSatisfaction',
            'totalKnowledgeHits'
        ));
    }

    public function create()
    {
        return view('ai.performance.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'metric_date' => 'required|date|unique:ai_performance_metrics,metric_date',
            'total_queries' => 'required|integer|min:0',
            'successful_responses' => 'required|integer|min:0',
            'failed_responses' => 'required|integer|min:0',
            'average_response_time' => 'required|numeric|min:0',
            'user_satisfaction_score' => 'required|numeric|min:0|max:5',
            'knowledge_base_hits' => 'required|integer|min:0',
            'new_learnings' => 'required|integer|min:0',
            'top_topics' => 'nullable|string',
            'improvement_areas' => 'nullable|string',
        ]);

        AiPerformanceMetric::create($validated);

        return redirect()->route('ai.performance.index')
            ->with('success', 'Metric created successfully.');
    }

    public function show(AiPerformanceMetric $performance)
    {
        return view('ai.performance.show', compact('performance'));
    }

    public function edit(AiPerformanceMetric $performance)
    {
        return view('ai.performance.edit', compact('performance'));
    }

    public function update(Request $request, $id)
    {
        $aiPerformanceMetric = AiPerformanceMetric::findOrFail($id);
        
        $validated = $request->validate([
            'metric_date' => 'required|date|unique:ai_performance_metrics,metric_date,'.$id,
            'total_queries' => 'required|integer|min:0',
            'successful_responses' => 'required|integer|min:0',
            'failed_responses' => 'required|integer|min:0',
            'average_response_time' => 'required|numeric|min:0',
            'user_satisfaction_score' => 'required|numeric|min:0|max:5',
            'knowledge_base_hits' => 'required|integer|min:0',
            'new_learnings' => 'required|integer|min:0',
            'top_topics' => 'nullable|string',
            'improvement_areas' => 'nullable|string',
        ]);

        $aiPerformanceMetric->update($validated);

        return redirect()->route('ai.performance.index')
            ->with('success', 'Metric updated successfully.');
    }

    public function destroy($id)
    {
        $aiPerformanceMetric = AiPerformanceMetric::findOrFail($id);
        $aiPerformanceMetric->delete();

        return redirect()->route('ai.performance.index')
            ->with('success', 'Metric deleted successfully.');
    }
}
