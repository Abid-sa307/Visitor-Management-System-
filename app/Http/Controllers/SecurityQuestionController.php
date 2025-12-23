<?php

namespace App\Http\Controllers;

use App\Models\SecurityQuestion;
use App\Models\Company;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecurityQuestionController extends Controller
{
    private function isSuper(): bool
    {
        $u = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
        return (($u->role ?? null) === 'superadmin');
    }

    public function index(Request $request)
    {
        $user = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
        
        $query = SecurityQuestion::with(['company', 'branch']);
        
        if (!$this->isSuper()) {
            $query->where('company_id', $user->company_id);
        }
        
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        
        $questions = $query->orderBy('sort_order')->paginate(10);
        
        $companies = $this->isSuper() ? Company::all() : collect([$user->company]);
        $branches = collect();
        
        if ($request->filled('company_id')) {
            $branches = Branch::where('company_id', $request->company_id)->get();
        } elseif (!$this->isSuper()) {
            $branches = Branch::where('company_id', $user->company_id)->get();
        }
        
        return view('security-questions.index', compact('questions', 'companies', 'branches'));
    }

    public function create()
    {
        $user = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
        $companies = $this->isSuper() ? Company::all() : collect([$user->company]);
        $branches = collect();
        
        if (!$this->isSuper()) {
            $branches = Branch::where('company_id', $user->company_id)->get();
        }
        
        return view('security-questions.create', compact('companies', 'branches'));
    }

    public function store(Request $request)
    {
        $user = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
        
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'branch_id' => 'nullable|exists:branches,id',
            'question' => 'required|string|max:255',
            'type' => 'required|in:text,yes_no,multiple_choice',
            'options' => 'nullable|array',
            'is_required' => 'boolean',
            'is_active' => 'boolean'
        ]);
        
        if (!$this->isSuper()) {
            $validated['company_id'] = $user->company_id;
        }
        
        $validated['sort_order'] = SecurityQuestion::where('company_id', $validated['company_id'])->max('sort_order') + 1;
        
        SecurityQuestion::create($validated);
        
        return redirect()->route('security-questions.index')->with('success', 'Security question created successfully.');
    }

    public function edit(SecurityQuestion $securityQuestion)
    {
        $user = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
        
        if (!$this->isSuper() && $securityQuestion->company_id !== $user->company_id) {
            abort(403);
        }
        
        $companies = $this->isSuper() ? Company::all() : collect([$user->company]);
        $branches = Branch::where('company_id', $securityQuestion->company_id)->get();
        
        return view('security-questions.edit', compact('securityQuestion', 'companies', 'branches'));
    }

    public function update(Request $request, SecurityQuestion $securityQuestion)
    {
        $user = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
        
        if (!$this->isSuper() && $securityQuestion->company_id !== $user->company_id) {
            abort(403);
        }
        
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'branch_id' => 'nullable|exists:branches,id',
            'question' => 'required|string|max:255',
            'type' => 'required|in:text,yes_no,multiple_choice',
            'options' => 'nullable|array',
            'is_required' => 'boolean',
            'is_active' => 'boolean'
        ]);
        
        if (!$this->isSuper()) {
            $validated['company_id'] = $user->company_id;
        }
        
        $securityQuestion->update($validated);
        
        return redirect()->route('security-questions.index')->with('success', 'Security question updated successfully.');
    }

    public function destroy(SecurityQuestion $securityQuestion)
    {
        $user = Auth::guard('company')->check() ? Auth::guard('company')->user() : Auth::user();
        
        if (!$this->isSuper() && $securityQuestion->company_id !== $user->company_id) {
            abort(403);
        }
        
        $securityQuestion->delete();
        
        return redirect()->route('security-questions.index')->with('success', 'Security question deleted successfully.');
    }
}