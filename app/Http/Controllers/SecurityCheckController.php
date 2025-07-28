<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SecurityCheck;
use App\Models\Visitor;

class SecurityCheckController extends Controller
{
    public function index()
    {
        $checks = SecurityCheck::with('visitor')->latest()->paginate(10);
        return view('security_checks.index', compact('checks'));
    }

    public function create($visitorId)
    {
        $visitor = Visitor::findOrFail($visitorId);
        return view('visitors.security', compact('visitor')); // ✅ Blade path updated
    }

    public function store(Request $request)
    {
        $request->validate([
            'visitor_id' => 'required|exists:visitors,id',
            'questions' => 'required|array',
            'responses' => 'required|array',
            'security_officer_name' => 'required|string|max:255',
        ]);

        SecurityCheck::create([
            'visitor_id' => $request->visitor_id,
            'questions' => json_encode($request->questions),
            'responses' => json_encode($request->responses),
            'security_officer_name' => $request->security_officer_name,
        ]);

        return redirect()->route('security-checks.index')->with('success', 'Security Check saved.');
    }
}
