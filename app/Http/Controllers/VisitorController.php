<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Company;
use App\Models\Department;
use App\Models\VisitorCategory;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function index()
    {
        $visitors = Visitor::latest()->paginate(10);
        return view('visitors.index', compact('visitors'));
    }

    public function create()
    {
        $companies = Company::all();
        $departments = Department::all();
        $categories = VisitorCategory::all();
        return view('visitors.create', compact('companies', 'departments', 'categories'));
    }

 public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'nullable|email',
        'phone' => 'required|string|max:15',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'documents' => 'nullable|array',
        'documents.*' => 'file|max:5120',
    ]);

    // Handle photo
    if ($request->hasFile('photo')) {
        $validated['photo'] = $request->file('photo')->store('photos', 'public');
    }

    // Handle documents
    if ($request->hasFile('documents')) {
        $paths = [];
        foreach ($request->file('documents') as $doc) {
            $paths[] = $doc->store('documents', 'public');
        }
        $validated['documents'] = $paths;
    }

    // Set default status
    $validated['status'] = 'Pending';

    Visitor::create($validated);

    return redirect()->route('visitors.index')->with('success', 'Visitor added successfully.');
}


    public function edit(Visitor $visitor)
    {
        $companies = Company::all();
        $departments = Department::all();
        $categories = VisitorCategory::all();
        return view('visitors.edit', compact('visitor', 'companies', 'departments', 'categories'));
    }

    public function update(Request $request, Visitor $visitor)
{
    $validated = $request->validate([
        'company_id' => 'required|exists:companies,id',
        'name' => 'required|string|max:255',
        'visitor_category_id' => 'nullable|exists:visitor_categories,id',
        'email' => 'nullable|email',
        'phone' => 'required|string|max:15',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'department_id' => 'nullable|exists:departments,id',
        'purpose' => 'nullable|string|max:255',
        'person_to_visit' => 'nullable|string|max:255',
        'in_time' => 'nullable|date',
        'out_time' => 'nullable|date',
        'status' => 'required|in:Pending,Approved,Rejected',
        'documents' => 'nullable|array',
        'documents.*' => 'file|max:5120',
        'visitor_company' => 'nullable|string|max:255',
        'visitor_website' => 'nullable|string|max:255',
        'vehicle_type' => 'nullable|string|max:20',
        'vehicle_number' => 'nullable|string|max:50',
        'goods_in_car' => 'nullable|string|max:255',
        'workman_policy' => 'nullable|in:Yes,No',
        'workman_policy_photo' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('photo')) {
        $validated['photo'] = $request->file('photo')->store('photos', 'public');
    }

    if ($request->hasFile('documents')) {
        $paths = [];
        foreach ($request->file('documents') as $doc) {
            $paths[] = $doc->store('documents', 'public');
        }
        $validated['documents'] = $paths;
    }

    if ($request->hasFile('workman_policy_photo')) {
        $validated['workman_policy_photo'] = $request->file('workman_policy_photo')->store('wpc_photos', 'public');
    }

    $visitor->update($validated);

    return redirect()->route('visitors.index')->with('success', 'Visitor updated successfully.');
}




    public function destroy(Visitor $visitor)
    {
        $visitor->delete();
        return redirect()->route('visitors.index')->with('success', 'Visitor deleted successfully.');
    }

    public function history(Request $request)
    {
        $query = Visitor::query();

        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        if ($request->has('company_id') && $request->company_id !== null) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->has('from') && $request->has('to')) {
            $query->whereBetween('in_time', [$request->from, $request->to]);
        }

        $visitors = $query->latest()->paginate(10);
        $companies = Company::all();

        return view('visitors.history', compact('visitors', 'companies'));
    }

    public function visitForm($id)
    {
        $visitor = Visitor::findOrFail($id);
        $departments = Department::all();
        $companies = Company::all();
        return view('visitors.visit', compact('visitor', 'departments', 'companies'));
    }

    public function submitVisit(Request $request, $id)
    {
        $visitor = Visitor::findOrFail($id);

        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'required|exists:departments,id',
            'person_to_visit' => 'required|string',
            'visitor_company' => 'nullable|string',
            'visitor_website' => 'nullable|url',
            'vehicle_type' => 'nullable|string',
            'vehicle_number' => 'nullable|string',
            'goods_in_car' => 'nullable|string',
            'workman_policy' => 'nullable|in:Yes,No',
            'workman_policy_photo' => 'nullable|image|max:2048',
            'status' => 'required|in:Pending,Approved,Rejected',
        ]);

        if ($request->hasFile('workman_policy_photo')) {
            $wpcPhoto = $request->file('workman_policy_photo')->store('wpc_photos', 'public');
            $visitor->workman_policy_photo = $wpcPhoto;
        }

        $visitor->update($request->except('workman_policy_photo') + [
            'workman_policy_photo' => $visitor->workman_policy_photo ?? null,
        ]);

        return redirect()->route('visitors.index')->with('success', 'Visitor visit info updated!');
    }


    public function entryPage()
    {
        $visitors = Visitor::orderByDesc('created_at')->paginate(10);
        return view('visitors.entry', compact('visitors'));
    }

    public function toggleEntry($id)
    {
        if (auth()->user()->role === 'guard') {
            abort(403, 'Unauthorized action.');
        }

        $visitor = Visitor::findOrFail($id);

        if (!$visitor->in_time) {
            $visitor->in_time = now();
            $visitor->status = 'Approved';
        } elseif (!$visitor->out_time) {
            $visitor->out_time = now();
            $visitor->status = 'Completed';
        }

        $visitor->save();

        return back()->with('success', 'Visitor entry updated.');
    }    

    public function printPass($id)
    {
        $visitor = Visitor::with('company')->findOrFail($id);
        return view('visitors.pass', compact('visitor'));
    }

    public function report()
    {
        $today = \Carbon\Carbon::today();
        $month = \Carbon\Carbon::now()->month;

        $todayVisitors = Visitor::whereDate('created_at', $today)->count();
        $monthVisitors = Visitor::whereMonth('created_at', $month)->count();

        $statusCounts = Visitor::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        $visitors = Visitor::latest()->paginate(10);
        return view('visitors.report', compact('visitors'));    
    }

    public function approvals()
    {
        $pendingVisitors = Visitor::where('status', 'Pending')->latest()->paginate(10);
        return view('visitors.approvals', compact('pendingVisitors'));
    }

    
}
