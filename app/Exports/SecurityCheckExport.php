<?php

namespace App\Exports;

use App\Models\SecurityCheck;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SecurityCheckExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = SecurityCheck::with(['visitor.company', 'visitor.department']);

        if ($this->request->filled('from')) {
            $query->whereDate('created_at', '>=', $this->request->from);
        }
        if ($this->request->filled('to')) {
            $query->whereDate('created_at', '<=', $this->request->to);
        }

        if (auth()->user()->role !== 'superadmin') {
            $query->whereHas('visitor', function($q) {
                $q->where('company_id', auth()->user()->company_id);
            });
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Date',
            'Visitor Name',
            'Company',
            'Department',
            'Security Officer',
            'Questions Answered',
            'Created At'
        ];
    }

    public function map($securityCheck): array
    {
        return [
            $securityCheck->created_at->format('Y-m-d H:i'),
            $securityCheck->visitor->name ?? 'N/A',
            $securityCheck->visitor->company->name ?? 'N/A',
            $securityCheck->visitor->department->name ?? 'N/A',
            $securityCheck->security_officer_name,
            count($securityCheck->responses ?? []),
            $securityCheck->created_at->format('Y-m-d H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9EAD3']
                ]
            ]
        ];
    }
}
