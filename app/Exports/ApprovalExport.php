<?php

namespace App\Exports;

use App\Models\Visitor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;

class ApprovalExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request ?? request();
    }

    public function collection()
    {
        $query = Visitor::whereNotNull('approved_at')
            ->with(['company', 'department', 'branch', 'approvedBy', 'rejectedBy']);

        $from = $this->request->input('from');
        $to   = $this->request->input('to');

        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        if ($this->request->filled('company_id')) {
            $query->where('company_id', $this->request->company_id);
        }

        if ($this->request->filled('branch_id')) {
            $branchIds = is_array($this->request->branch_id) ? $this->request->branch_id : [$this->request->branch_id];
            $query->whereIn('branch_id', $branchIds);
        }

        if ($this->request->filled('department_id')) {
            $departmentIds = is_array($this->request->department_id) ? $this->request->department_id : [$this->request->department_id];
            $query->whereIn('department_id', $departmentIds);
        }

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        // Non-superadmin: restrict to own company
        if (auth()->user()->role !== 'superadmin') {
            $query->where('company_id', auth()->user()->company_id);
            if (auth()->user()->departments->isNotEmpty()) {
                $query->whereIn('department_id', auth()->user()->departments->pluck('id'));
            }
        }

        return $query->latest('approved_at')->get();
    }

    public function headings(): array
    {
        return [
            'Name', 'Email', 'Phone', 'Company', 'Branch', 'Department',
            'Status', 'Approved / Rejected By', 'Approval / Rejection Date',
            'Visit Date', 'Purpose',
        ];
    }

    public function map($v): array
    {
        $handledBy = null;
        $handledAt = null;
        if ($v->status === 'Approved' && $v->approvedBy) {
            $handledBy = $v->approvedBy->name;
            $handledAt = $v->approved_at ? \Carbon\Carbon::parse($v->approved_at)->format('Y-m-d H:i:s') : 'N/A';
        } elseif ($v->status === 'Rejected' && $v->rejectedBy) {
            $handledBy = $v->rejectedBy->name;
            $handledAt = $v->rejected_at ? \Carbon\Carbon::parse($v->rejected_at)->format('Y-m-d H:i:s') : 'N/A';
        }

        return [
            $v->name,
            $v->email ?? 'N/A',
            $v->phone ?? 'N/A',
            $v->company->name ?? 'N/A',
            $v->branch->name ?? 'N/A',
            $v->department->name ?? 'N/A',
            $v->status ?? 'N/A',
            $handledBy ?? 'N/A',
            $handledAt ?? 'N/A',
            $v->visit_date ? \Carbon\Carbon::parse($v->visit_date)->format('Y-m-d') : 'N/A',
            $v->purpose ?? 'N/A',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A:K' => ['alignment' => ['wrapText' => true]],
        ];
    }
}
