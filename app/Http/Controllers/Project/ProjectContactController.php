<?php

namespace App\Http\Controllers\Project;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectContact;
use App\Models\Project;



class ProjectContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   // app/Http/Controllers/ProjectContactController.php
public function index($projectId)
{
     $contacts = ProjectContact::where('project_id', $projectId)->get();
    return response()->json($contacts);
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



        // إنشاء جهة اتصال جديدة
    // In ProjectContactController.php

public function store(Request $request, $projectId)
{
    // Validate the request data here if needed
    // $request->validate([ 'name' => 'required', 'email' => 'email' ]);

    // Merge the project_id from the route into the request data
    $data = $request->all();
    $data['project_id'] = $projectId;

    // Create the contact with the merged data
    $contact = ProjectContact::create($data);

    return response()->json([
        'success' => true,
        'message' => '✅ Contact created successfully',
        'contact' => $contact
    ]);
}


    /**
     * Display the specified resource.
     */


    public function show($id)
{
    $contact = ProjectContact::findOrFail($id);
    return response()->json($contact);
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

public function update(Request $request, Project $project, ProjectContact $contact)
{
    // هنا، لا تحتاج للبحث عن جهة الاتصال باستخدام find($id).
    // Laravel سيقوم بذلك تلقائيًا عبر Route Model Binding.
    // المتغير $contact أصبح الآن يمثل كائن جهة الاتصال الذي تريد تحديثه.

    $contact->name = $request->name;
    $contact->email = $request->email;
    $contact->phone = $request->phone;
    $contact->mobile = $request->mobile;
    $contact->position = $request->position;
    $contact->is_primary = $request->is_primary;
    $contact->save();

    return response()->json([
        'success' => true,
        'message' => '✅ Contact updated successfully',
        'contact' => $contact
    ]);
}
    /**
     * Update the specified resource in storage.
     */
     public function destroy($id)
    {
        $contact = ProjectContact::findOrFail($id);
        $contact->delete();

        return response()->json(['message' => 'Contact deleted']);
    }

    /**
     * Remove the specified resource from storage.
     */
public function deleteMultiple(Request $request)
{
    $ids = $request->ids; // استقبل الـ IDs من AJAX

    if (!$ids || !is_array($ids)) {
        return response()->json(['error' => 'No IDs provided'], 400);
    }

    // حذف من قاعدة البيانات
    ProjectContact::whereIn('id', $ids)->delete();

    return response()->json(['success' => true]);
}


}
