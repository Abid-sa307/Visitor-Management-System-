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
    /**
     * Display the public visitor page for a company
     */
    public function scan(Company $company, $branch = null)
    {
        $branchModel = null;
        if ($branch) {
            $branchModel = $company->branches()->find($branch);
        }

        $visitors = Visitor::where('company_id', $company->id)
            ->when($branchModel, function($query) use ($branchModel) {
                return $query->where('branch_id', $branchModel->id);
            })
            ->latest()
            ->paginate(10);

        return view('visitors.public-index', [
            'company' => $company,
            'branch' => $branchModel,
            'visitors' => $visitors
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
        
        return view('visitors.public-visit', [
            'company' => $company,
            'branch' => $branchModel
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
}
