<?php
namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\Uncertainty;
use Illuminate\Http\Request;

class UncertaintyController extends Controller
{
    /**
     * Display a listing of the resource.
     */


   public function store(Request $request)
{
    $history = Uncertainty::create([
        'test_id' => $request->test_id,
        'value' => $request->value,
        'date_recorded' => $request->date_recorded,
    ]);

    return response()->json($history);
}

 public function index($testId)
{
    return Uncertainty::where('test_id', $testId)
           ->orderBy('date_recorded', 'desc')
           ->orderBy('id', 'desc') // ترتيب حسب ID لو التواريخ متساوية
           ->get();

}


    public function destroy($id)
    {
        $history = Uncertainty::findOrFail($id);
        $history->delete();
        return response()->json(['message' => 'Deleted']);
    }


}
