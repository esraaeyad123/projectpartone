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
        'customer_id' => 'nullable|exists:customers,id',
        'owner_id' => 'nullable|exists:customers,id',
        'consultant_id' => 'nullable|exists:customers,id',
        'contractor_id' => 'nullable|exists:customers,id',
        'projectArabicLocation' => 'nullable|string|max:255',
    ]);

    $project->update([
        'name' => $validated['name'],
        'arabic_name' => $validated['arabic_name'] ?? null,
        'registration_date' => $validated['registration_date'] ?? null,
        'customer_id' => $validated['customer_id'] ?? null,
        'owner_id' => $validated['owner_id'] ?? null,
        'consultant_id' => $validated['consultant_id'] ?? null,
        'contractor_id' => $validated['contractor_id'] ?? null,
        'projectArabicLocation' => $validated['projectArabicLocation'] ?? null,
    ]);

    return response()->json([
        'id' => $project->id,
        'project' => $project,
        'arabic_name' => $project->arabic_name
    ]);
}



public function show(Project $project)
{
    // قم بتحميل علاقة جهات الاتصال المرتبطة بالمشروع
    $project->load('contacts');
    // الآن، الكائن $project يحتوي على بيانات جهات الاتصال
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


