<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Visitor;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRController extends Controller
{
    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'layouts.guest';
    public function scan(Company $company, $branch = null, Request $request = null)
{
    $request = $request ?? request();
    $branchModel = $branch ? $company->branches()->find($branch) : null;

    // Get visitor from session
    $visitor = null;
    if (session('current_visitor_id')) {
        $visitor = Visitor::find(session('current_visitor_id'));
        
        // Check if visitor exists and is checked out
        if ($visitor && $visitor->status === 'checked_out') {
            // Clear the visitor from session
            $request->session()->forget('current_visitor_id');
            $visitor = null;
        } elseif (!$visitor) {
            // If visitor not found, clear the session
            $request->session()->forget('current_visitor_id');
        }
    }

    return view('visitors.public-index', [
        'company' => $company,
        'branch' => $branchModel,
        'visitor' => $visitor
    ]);
}

    /**
     * Show the form for creating a new visitor
     */
    public function createVisitor(Company $company)
    {
        return view('visitors.public-create', [
            'company' => $company
        ]);
    }

    /**
     * Show the visit form for existing visitors
     */
    public function showVisitForm(Company $company, $branch = null)
    {
        $branchModel = null;
        if ($branch) {
            $branchModel = $company->branches()->find($branch);
        }
        
        // Get departments for the company
        $departments = $company->departments()->get();
        
        // Get branches for the company
        $branches = $company->branches()->get();
        
        return view('visitors.public-visit', [
            'company' => $company,
            'branch' => $branchModel,
            'departments' => $departments,
            'branches' => $branches,
            'visitor' => new \App\Models\Visitor() // Add empty visitor to prevent undefined variable errors
        ]);
    }

    /**
     * Download QR code for a company
     */
    public function downloadQR(Company $company)
    {
        // Get the base URL
        $url = route('qr.scan', $company);
        
        // Get branch information if available
        $branch = request()->has('branch_id') 
            ? \App\Models\Branch::find(request('branch_id'))
            : null;
        
        // Create human-readable text for QR code
        $qrText = "Company: " . $company->name . "\n";
        $qrText .= "Contact: " . $company->contact_number . "\n";
        $qrText .= "Email: " . $company->email . "\n";
        
        if ($branch) {
            $qrText .= "\nBranch: " . $branch->name . "\n";
            $qrText .= "Address: " . $branch->address . "\n";
            $qrText .= "Contact: " . $branch->phone . "\n";
        }
        
        // Add the URL at the end
        $qrText .= "\nScan to check-in: " . $url;
        
        // Create a renderer with SVG backend
        $renderer = new \BaconQrCode\Renderer\Image\SvgImageBackEnd();
        $image = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(300),
            $renderer
        );
        
        // Create writer and generate QR code with the formatted text
        $writer = new \BaconQrCode\Writer($image);
        $qrCode = $writer->writeString($qrText);
        
        // Generate filename with company (and branch if available)
        $filename = \Illuminate\Support\Str::slug($company->name);
        if ($branch) {
            $filename .= '-' . \Illuminate\Support\Str::slug($branch->name);
        }
        $filename .= '-qrcode.svg';
        
        return response($qrCode, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }
    
    /**
     * Store a newly created visitor in storage.
     */
    public function storeVisitor(Request $request, Company $company)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'purpose' => 'nullable|string|max:1000',
            'documents.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:5120',
            'face_encoding' => 'nullable|json',
            'face_image' => 'nullable|string',
        ]);
        
        // Set default values for required fields if they're empty
        $validated['name'] = $validated['name'] ?? 'Guest Visitor';
        $validated['phone'] = $validated['phone'] ?? 'Not Provided';
        $validated['purpose'] = $validated['purpose'] ?? 'General Visit';
        
        try {
            // Create the visitor
            $visitor = new Visitor([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'purpose' => $validated['purpose'],
                'company_id' => $company->id,
                'face_encoding' => $validated['face_encoding'],
                'is_approved' => $company->auto_approve_visitors,
            ]);
            
            // Handle face image
            if (!empty($validated['face_image'])) {
                $imageData = $validated['face_image'];
                if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                    $imageData = substr($imageData, strpos($imageData, ',') + 1);
                    $type = strtolower($type[1]);
                    
                    if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
                        throw new \Exception('Invalid image type');
                    }
                    
                    $imageData = base64_decode($imageData);
                    if ($imageData === false) {
                        throw new \Exception('Base64 decode failed');
                    }
                    
                    $fileName = 'face_' . time() . '_' . uniqid() . '.' . $type;
                    $path = 'visitor-faces/' . $fileName;
                    
                    // Save the file to storage
                    \Storage::disk('public')->put($path, $imageData);
                    
                    $visitor->face_image = $path;
                }
            }
            
            // Save the visitor
            $visitor->save();
            
            // Store visitor ID in session
            session(['current_visitor_id' => $visitor->id]);
            
            // Handle document uploads
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    $path = $file->store('visitor-documents', 'public');
                    $visitor->documents()->create([
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }
            
            // Redirect with success message
            return redirect()
                ->route('qr.scan', $company)
                ->with('success', 'Visitor registered successfully!' . 
                    ($company->auto_approve_visitors ? ' Your visit has been approved.' : ' Please wait for approval.'));
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error registering visitor: ' . $e->getMessage());
        }
    }

public function storeVisit(Request $request, Company $company, $branch = null)
{
    $validated = $request->validate([
        'visitor_id' => 'required|exists:visitors,id',
        'department_id' => 'required|exists:departments,id',
        'person_to_visit' => 'required|string|max:255',
        'purpose' => 'required|string',
        'vehicle_number' => 'nullable|string|max:50',
        'documents' => 'nullable|array',
        'documents.*' => 'file|max:2048',
    ]);

    // Find the visitor
    $visitor = Visitor::findOrFail($request->visitor_id);
    
    // Update visitor details
    $visitor->update([
        'department_id' => $validated['department_id'],
        'person_to_visit' => $validated['person_to_visit'],
        'purpose' => $validated['purpose'],
        'vehicle_number' => $validated['vehicle_number'] ?? null,
        'status' => 'checked_in',
        'in_time' => now(),
    ]);

    // Handle document uploads if any
    if ($request->hasFile('documents')) {
        $documents = [];
        foreach ($request->file('documents') as $file) {
            $path = $file->store('visitor_documents', 'public');
            $documents[] = [
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'type' => $file->getClientMimeType()
            ];
        }
        $visitor->documents = $documents;
        $visitor->save();
    }

    // Redirect to the scan page with the company ID
    return redirect()->route('qr.scan', ['company' => $company->id])->with('success', 'Visit details submitted successfully!');
}

/**
 * Show the public visit form (no auth required)
 */
public function showPublicVisitForm(Company $company, $branch = null, Request $request = null)
{
    $request = $request ?? request(); // Get the request instance if not injected
    
    $branchModel = null;
    if ($branch) {
        $branchModel = $company->branches()->find($branch);
    }
    
    // Get visitor ID from query parameter
    $visitorId = $request->query('visitor');
    
    if (!$visitorId) {
        return redirect()->back()->with('error', 'No visitor specified.');
    }
    
    $visitor = Visitor::find($visitorId);
    
    if (!$visitor) {
        return redirect()->back()->with('error', 'Visitor not found.');
    }
    
    // Store visitor ID in session
    session(['current_visitor_id' => $visitor->id]);
    
    // Get departments, branches and visitor categories
    $departments = $company->departments()->get();
    $branches = $company->branches()->get();
    
    // Get active visitor categories for the company
    $visitorCategories = \App\Models\VisitorCategory::where('company_id', $company->id)
        ->where('is_active', true)
        ->orderBy('name')
        ->get();
    
    return view('visitors.public-visit', [
        'company' => $company,
        'branch' => $branchModel,
        'departments' => $departments,
        'visitorCategories' => $visitorCategories,
        'branches' => $branches,
        'visitor' => $visitor
    ]);
}

/**
 * Handle public visit form submission (no auth required)
 */
public function storePublicVisit(Request $request, Company $company, $branch = null)
{
    // Get visitor ID from form input or route parameter
    $visitorId = $request->input('visitor_id') ?? $request->route('visitor');
    
    $validated = $request->validate([
        'visitor_id' => 'required|exists:visitors,id',
        'department_id' => 'required|exists:departments,id',
        'person_to_visit' => 'required|string|max:255',
        'purpose' => 'required|string',
        'vehicle_number' => 'nullable|string|max:50',
        'documents' => 'nullable|array',
        'documents.*' => 'file|max:2048',
    ], [
        'visitor_id.required' => 'Visitor information is required.',
        'visitor_id.exists' => 'Invalid visitor information provided.'
    ]);

    // Find the visitor
    $visitor = Visitor::findOrFail($request->visitor_id);
    
    // Update visitor details
    $visitor->update([
        'department_id' => $validated['department_id'],
        'person_to_visit' => $validated['person_to_visit'],
        'purpose' => $validated['purpose'],
        'vehicle_number' => $validated['vehicle_number'] ?? null,
        'status' => 'checked_in',
        'in_time' => now(),
    ]);

    // Handle document uploads if any
    if ($request->hasFile('documents')) {
        $documents = [];
        foreach ($request->file('documents') as $file) {
            $path = $file->store('visitor_documents', 'public');
            $documents[] = [
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'type' => $file->getClientMimeType()
            ];
        }
        $visitor->documents = $documents;
        $visitor->save();
    }

    // Store visitor in session
    session(['current_visitor_id' => $visitor->id]);

    // Redirect to the scan page with success message
    return redirect()->route('qr.scan', ['company' => $company->id])->with('success', 'Visit details submitted successfully!');
}
}
