<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Company;
use App\Models\Department;
use App\Models\VisitorCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class VisitorController extends Controller
{
    private function companyScope($query)
    {
        if (auth()->user()->role !== 'super_admin') {
            $query->where('company_id', auth()->user()->company_id);
        }
        return $query;
    }

    private function getCompanies()
    {
        return auth()->user()->role === 'super_admin'
            ? Company::all()
            : Company::where('id', auth()->user()->company_id)->get();
    }

    private function getDepartments()
    {
        return auth()->user()->role === 'super_admin'
            ? Department::all()
            : Department::where('company_id', auth()->user()->company_id)->get();
    }

    public function index()
    {
        $visitors = $this->companyScope(Visitor::latest())->paginate(10);
        return view('visitors.index', compact('visitors'));
    }

    public function create()
    {
        $companies = $this->getCompanies();
        $departments = $this->getDepartments();
        $categories = VisitorCategory::all();
        return view('visitors.create', compact('companies', 'departments', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'nullable|email',   
            'phone'  => 'required|string|max:15',
            'photo'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'documents'   => 'nullable|array',
            'documents.*' => 'file|max:5120',
        ]);

        // Assign company for non-superadmin
        if (auth()->user()->role !== 'superadmin') {
            $validated['company_id'] = auth()->user()->company_id;
        }

        // Photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photos', 'public');
        }

        // Document upload
        if ($request->hasFile('documents')) {
            $paths = [];
            foreach ($request->file('documents') as $doc) {
                $paths[] = $doc->store('documents', 'public');
            }
            $validated['documents'] = $paths;
        }

        $validated['status'] = 'Pending';
        Visitor::create($validated);

        // ✅ Robust redirection
        $user = auth()->user();

        if ($user->role === 'superadmin') {
            return redirect()->route('visitors.index')->with('success', 'Visitor registered successfully!');
        }

        if ($user->role === 'company') {
            return redirect()->route('company.visitors.index')->with('success', 'Visitor registered successfully!');
        }

        // fallback
        return redirect('/')->with('error', 'Unauthorized role.');
    }



    public function edit(Visitor $visitor)
    {
        $this->authorizeVisitor($visitor);

        $companies   = $this->getCompanies();
        $departments = $this->getDepartments();
        $categories  = VisitorCategory::all();

        return view('visitors.edit', compact('visitor', 'companies', 'departments', 'categories'));
    }

    public function update(Request $request, Visitor $visitor)
    {
        $this->authorizeVisitor($visitor);

        $validated = $request->validate([
            'company_id'          => 'required|exists:companies,id',
            'name'                => 'required|string|max:255',
            'visitor_category_id' => 'nullable|exists:visitor_categories,id',
            'email'               => 'nullable|email',
            'phone'               => 'required|string|max:15',
            'photo'               => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'department_id'       => 'nullable|exists:departments,id',
            'purpose'             => 'nullable|string|max:255',
            'person_to_visit'     => 'nullable|string|max:255',
            'in_time'             => 'nullable|date',
            'out_time'            => 'nullable|date',
            'status'              => 'required|in:Pending,Approved,Rejected',
            'documents'           => 'nullable|array',
            'documents.*'         => 'file|max:5120',
            'visitor_company'     => 'nullable|string|max:255',
            'visitor_website'     => 'nullable|string|max:255',
            'vehicle_type'        => 'nullable|string|max:20',
            'vehicle_number'      => 'nullable|string|max:50',
            'goods_in_car'        => 'nullable|string|max:255',
            'workman_policy'      => 'nullable|in:Yes,No',
            'workman_policy_photo'=> 'nullable|image|max:2048',
        ]);

        if (auth()->user()->role !== 'superadmin') {
            $validated['company_id'] = auth()->user()->company_id;
        }

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
        
        $user = auth()->user();

        if ($user->role === 'superadmin'){
            return redirect()->route('visitors.index')->with('success', 'Visitor updated successfully!');
        }

        if ($user->role === 'company') {
            return redirect()->route('company.visitors.index')->with('success', 'Visitor updated successfully!');
        }

        return redirect('/')->with('error', 'Uauthorized role.');
        // return redirect()->to(panel_route('visitors.index'))
        //     ->with('success', 'Visitor updated successfully.');

            
        // $user = auth()->user();

        // if ($user->role === 'superadmin') {
        //     return redirect()->route('visitors.index')->with('success', 'Visitor registered successfully!');
        // }

        // if ($user->role === 'company') {
        //     return redirect()->route('company.visitors.index')->with('success', 'Visitor registered successfully!');
        // }

        // // fallback
        // return redirect('/')->with('error', 'Unauthorized role.');
    }

    public function destroy(Visitor $visitor)
    {
        $this->authorizeVisitor($visitor);
        $visitor->delete();


        $user = auth()->user();

        if ($user->role === 'superadmin'){
            return redirect()->route('visitors.index')->with('success', 'Visitor deleted successfully!');
        }

        if ($user->role === 'company') {
            return redirect()->route('company.visitors.index')->with('success', 'Visitor deleted successfully!');
        }

        return redirect('/')->with('error', 'Uauthorized role.');
        // return redirect()->to(panel_route('visitors.index'))
        //     ->with('success', 'Visitor deleted successfully.');
    }

    public function history(Request $request)
    {
        $query = Visitor::query();
        $this->companyScope($query);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('in_time', [$request->from, $request->to]);
        }

        $visitors = $query->latest()->paginate(10);
        $companies = $this->getCompanies();

        $departments = $request->filled('company_id')
            ? Department::where('company_id', $request->company_id)->get()
            : $this->getDepartments();

        return view('visitors.history', compact('visitors', 'companies', 'departments'));
    }

    public function visitForm($id)
    {
        $visitor = Visitor::findOrFail($id);
        $this->authorizeVisitor($visitor);

        $companies = $this->getCompanies();
        $departments = $this->getDepartments();

        return view('visitors.visit', compact('visitor', 'departments', 'companies'));
    }

    public function submitVisit(Request $request, $id)
    {
        $visitor = Visitor::findOrFail($id);
        $this->authorizeVisitor($visitor);

        if (auth()->user()->role !== 'super_admin') {
            $request->merge(['company_id' => auth()->user()->company_id]);
        }

        $request->validate([
            'company_id'        => 'required|exists:companies,id',
            'department_id'     => 'required|exists:departments,id',
            'person_to_visit'   => 'required|string',
            'visitor_company'   => 'nullable|string',
            'visitor_website'   => 'nullable|url',
            'vehicle_type'      => 'nullable|string',
            'vehicle_number'    => 'nullable|string',
            'goods_in_car'      => 'nullable|string',
            'workman_policy'    => 'nullable|in:Yes,No',
            'workman_policy_photo' => 'nullable|image|max:2048',
            'status'            => 'required|in:Pending,Approved,Rejected',
        ]);

        if ($request->hasFile('workman_policy_photo')) {
            $visitor->workman_policy_photo = $request->file('workman_policy_photo')->store('wpc_photos', 'public');
        }

        $visitor->update($request->except('workman_policy_photo') + [
            'workman_policy_photo' => $visitor->workman_policy_photo ?? null,
        ]);

        return redirect()->to(panel_route('visitors.index'))
            ->with('success', 'Visit submitted successfully.');
    }

    public function entryPage()
    {
        $visitors = $this->companyScope(Visitor::orderByDesc('created_at'))->paginate(10);
        return view('visitors.entry', compact('visitors'));
    }

    public function toggleEntry($id)
    {
        if (auth()->user()->role === 'guard') {
            abort(403, 'Unauthorized action.');
        }

        $visitor = Visitor::findOrFail($id);
        $this->authorizeVisitor($visitor);

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
        $this->authorizeVisitor($visitor);
        return view('visitors.pass', compact('visitor'));
    }

    public function report()
    {
        $today = Carbon::today();
        $month = Carbon::now()->month;

        $todayVisitors = $this->companyScope(
            Visitor::whereDate('created_at', $today)
        )->count();

        $monthVisitors = $this->companyScope(
            Visitor::whereMonth('created_at', $month)
        )->count();

        $statusCounts = $this->companyScope(
            Visitor::select('status', DB::raw('count(*) as total'))->groupBy('status')
        )->pluck('total', 'status');

        $visitors = $this->companyScope(Visitor::latest())->paginate(10);

        return view('visitors.report', compact('visitors', 'todayVisitors', 'monthVisitors', 'statusCounts'));
    }

    public function approvals(Request $request)
{
    $query = Visitor::with(['company', 'department', 'category']); // eager load relations
    $this->companyScope($query);

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('department_id')) {
        $query->where('department_id', $request->department_id);
    }

    $visitors = $query->latest()->paginate(10);

    // Get departments for the dropdown
    $departments = \App\Models\Department::where('company_id', auth()->user()->company_id)->get();

    return view('visitors.approvals', compact('visitors', 'departments'));
}


    private function authorizeVisitor($visitor)
    {
        if (auth()->user()->role !== 'super_admin' && $visitor->company_id != auth()->user()->company_id) {
            abort(403, 'Unauthorized access.');
        }
    }
}
