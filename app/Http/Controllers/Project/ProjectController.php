<?php

namespace App\Http\Controllers\Project;
use App\Models\Project;
use App\Models\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index(Request $request)
{
    if ($request->expectsJson()) {
        // جلب المشاريع مع العميل وجهات الاتصال
        return Project::with(['contacts', 'customer'])->get();
    }

    $projects = Project::with(['contacts', 'customer'])->get();
    $customers = Customer::all();
    return view('projects.index', compact('projects', 'customers'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'arabic_name' => 'nullable|string|max:255',
        'registration_date' => 'nullable|date',
        'region' => 'nullable|string|max:255',
        'customer_id' => 'nullable|exists:customers,id',
        'owner' => 'nullable|string|max:255',
        'consultant' => 'nullable|string|max:255',
        'contractor' => 'nullable|string|max:255',
        'projectArabicLocation' => 'nullable|string|max:255',
    ]);

    $project = Project::create($validated);

return response()->json([
        'id' => $project->id,   // ✅ رجّع ID
        'project' => $project,
        'message' => 'Project created successfully'
    ]);

}

public function update(Request $request, Project $project)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'arabic_name' => 'nullable|string|max:255',
        'registration_date' => 'nullable|date',
        'region' => 'nullable|string|max:255',
        'customer_id' => 'nullable|exists:customers,id',
        'owner' => 'nullable|string|max:255',
        'consultant' => 'nullable|string|max:255',
        'contractor' => 'nullable|string|max:255',
        'projectArabicLocation' => 'nullable|string|max:255',
    ]);

    $project->update($validated);

    return response()->json(['project' => $project]);
}



public function show(Project $project)
{
    return response()->json($project);
}

public function destroy(Project $project)
{
    $project->delete();
    return response()->json(['message' => 'Deleted']);
}

public function deleteMultiple(Request $request)
{
    $ids = $request->ids;
    if ($ids && is_array($ids)) {
        Project::whereIn('id', $ids)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
    return response()->json(['message' => 'No projects selected'], 400);
}


}


