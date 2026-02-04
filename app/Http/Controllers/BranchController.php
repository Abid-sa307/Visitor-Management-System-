<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isSuper = $user->role === 'superadmin';

        $query = Branch::with('company')->latest();

        if (!$isSuper) {
            $query->where('company_id', $user->company_id);
        }

        if ($isSuper && request()->filled('company_id')) {
            $query->where('company_id', request('company_id'));
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhereHas('company', function ($c) use ($search) {
                      $c->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $branches = $query->paginate(10)->appends(request()->query());

        $companies = $isSuper
            ? Company::orderBy('name')->get()
            : Company::where('id', $user->company_id)->get();

        return view('branches.index', [
            'branches' => $branches,
            'companies' => $companies,
            'isSuper' => $isSuper,
        ]);
    }

    public function create()
    {
        $user = auth()->user();
        
        if ($user->role === 'superadmin') {
            $companies = Company::orderBy('name')->get();
        } else {
            $companies = Company::where('id', $user->company_id)->get();
        }

        return view('branches.create', compact('companies'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'superadmin') {
            $request->merge(['company_id' => auth()->user()->company_id]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        Branch::create($validated);

        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }

    public function edit(Branch $branch)
    {
        if (auth()->user()->role !== 'superadmin' && $branch->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized action.');
        }

        $companies = auth()->user()->role === 'superadmin'
            ? Company::orderBy('name')->get()
            : Company::where('id', auth()->user()->company_id)->get();

        return view('branches.edit', compact('branch', 'companies'));
    }

    public function update(Request $request, Branch $branch)
    {
        if (auth()->user()->role !== 'superadmin' && $branch->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized action.');
        }

        if (auth()->user()->role !== 'superadmin') {
            $request->merge(['company_id' => auth()->user()->company_id]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $branch->update($validated);

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        if (auth()->user()->role !== 'superadmin' && $branch->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($branch->departments()->exists() || $branch->users()->exists()) {
             return back()->with('error', 'Cannot delete branch because it has associated departments or users.');
        }

        $branch->delete();
        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully.');
    }

    // API Methods
    public function getByCompany(Company $company)
    {
        // Check both guards for authenticated user
        $user = Auth::guard('company')->check() ? Auth::guard('company')->user() : auth()->user();
        
        // Superadmin sees all branches
        if ($user && in_array($user->role, ['superadmin', 'super', 'super_admin'], true)) {
            $branches = $company->branches()->orderBy('name')->get(['id', 'name']);
        } else {
            // Company users see only their assigned branches
            $branches = $company->branches()
                ->whereHas('users', function($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->orderBy('name')
                ->get(['id', 'name']);
        }
        
        return response()->json($branches);
    }

    public function getDepartments(Branch $branch)
    {
        try {
            $query = $branch->departments()->orderBy('name');
            $user = auth('web')->user() ?: auth('company')->user();
            if ($user && $user->role !== 'superadmin') {
                if ($user->departments->isNotEmpty()) {
                    $query->whereIn('id', $user->departments->pluck('id'));
                }
            }
            return response()->json($query->get(['id', 'name']));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load departments', 'message' => $e->getMessage()], 500);
        }
    }
}
