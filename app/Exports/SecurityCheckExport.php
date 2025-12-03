<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;
use App\Models\SecurityCheck;

class SecurityCheckExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = SecurityCheck::with(['visitor.company', 'visitor.department', 'securityOfficer']);

        // Apply filters from request
        if ($this->request->filled('company_id')) {
            $query->whereHas('visitor', function($q) {
                $q->where('company_id', $this->request->company_id);
            });
        }

        if ($this->request->filled('department_id')) {
            $query->whereHas('visitor', function($q) {
                $q->where('department_id', $this->request->department_id);
            });
        }

        if ($this->request->filled('branch_id')) {
            $query->whereHas('visitor', function($q) {
                $q->where('branch_id', $this->request->branch_id);
            });
        }

        if ($this->request->filled('from')) {
            $query->whereDate('created_at', '>=', $this->request->from);
        }

        if ($this->request->filled('to')) {
            $query->whereDate('created_at', '<=', $this->request->to);
        }

        // Apply company filter for non-superadmins
        if (auth()->user()->role !== 'superadmin') {
            $query->whereHas('visitor', function($q) {
                $q->where('company_id', auth()->user()->company_id);
            });
        }

        return $query->latest('created_at')->get();
    }

    public function headings(): array
    {
        return [
            'Date & Time',
            'Visitor Name',
            'Visitor Phone',
            'Visitor Email',
            'Company',
            'Department',
            'Security Officer',
            'Status',
            'Checkpoint',
            'Responses',
            'Created At'
        ];
    }

    public function map($check): array
    {
        $responses = is_string($check->responses) ? json_decode($check->responses, true) : ($check->responses ?? []);
        $responseCount = is_countable($responses) ? count($responses) : 0;
        $status = $responseCount > 0 ? 'Completed' : 'Pending';
        
        return [
            $check->created_at->format('Y-m-d H:i:s'),
            $check->visitor->name ?? 'N/A',
            $check->visitor->phone ?? 'N/A',
            $check->visitor->email ?? 'N/A',
            $check->visitor->company->name ?? 'N/A',
            $check->visitor->department->name ?? 'N/A',
            $check->securityOfficer->name ?? 'N/A',
            $status,
            $check->checkpoint ?? 'N/A',
            json_encode($responses, JSON_PRETTY_PRINT),
            $check->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A:J' => ['alignment' => ['wrapText' => true]],
        ];
    }
}