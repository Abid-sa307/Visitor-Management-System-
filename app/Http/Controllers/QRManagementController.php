<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Branch;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class QRManagementController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Display a listing of companies with their QR codes.
     */
    public function index()
    {
        $companies = Company::withCount('branches')
            ->with(['branches' => function($query) {
                $query->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        return view('qr-management.index', compact('companies'));
    }

    /**
     * Display the QR code for a specific company or branch.
     */
    public function show(Company $company, Branch $branch = null)
    {
        $user = auth()->user();
        
        // Allow public access to view QR codes
        
        // If branch is provided, ensure it belongs to the company
        if ($branch && $branch->company_id !== $company->id) {
            abort(404);
        }
        
        $company->load(['branches' => function($query) {
            $query->orderBy('name');
        }]);
        
        return view('qr-management.show', compact('company', 'branch'));
    }

    /**
     * Download the QR code for a company or branch.
     */
    public function download(Company $company, Branch $branch = null)
    {
        // Allow public download of QR codes
        
        // If branch is provided, ensure it belongs to the company
        if ($branch && $branch->company_id !== $company->id) {
            abort(404);
        }

        $qrData = [
            'company' => $company->name,
            'email' => $company->email,
            'contact' => $company->contact_number,
            'branch' => $branch ? $branch->name : null,
            'branch_address' => $branch ? $branch->address : null,
            'checkin_url' => $branch 
                ? route('qr.scan', ['company' => $company, 'branch' => $branch])
                : route('qr.scan', $company)
        ];

        $qrText = "Company: " . $qrData['company'] . "\n";
        $qrText .= "Email: " . $qrData['email'] . "\n";
        $qrText .= "Contact: " . $qrData['contact'] . "\n";

        if ($branch) {
            $qrText .= "\nBranch: " . $qrData['branch'] . "\n";
            if (!empty($branch->address)) {
                $qrText .= "Address: " . $qrData['branch_address'] . "\n";
            }
        }

        $qrText .= "\nCheck-in URL: " . $qrData['checkin_url'];

        $qrCode = QrCode::format('svg')
            ->size(1000)
            ->generate($qrText);

        $filename = 'qr-code-' . 
                   Str::slug($company->name) . 
                   ($branch ? '-' . Str::slug($branch->name) : '') . 
                   '.svg';

        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
