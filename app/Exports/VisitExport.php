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

class VisitExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request ?? request();
    }

    public function collection()
    {
        $query = Visitor::whereNotNull('in_time')->with(['company', 'department', 'branch']);

        $from = $this->request->input('from') ?: now()->format('Y-m-d');
        $to   = $this->request->input('to')   ?: now()->format('Y-m-d');

        $query->whereDate('in_time', '>=', $from)
              ->whereDate('in_time', '<=', $to);

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

        if ($this->request->filled('visit_type')) {
            $query->where('purpose', $this->request->visit_type);
        }

        // Non-superadmin: restrict to own company
        if (auth()->user()->role !== 'superadmin') {
            $query->where('company_id', auth()->user()->company_id);
            if (auth()->user()->departments->isNotEmpty()) {
                $query->whereIn('department_id', auth()->user()->departments->pluck('id'));
            }
        }

        return $query->latest('in_time')->get();
    }

    public function headings(): array
    {
        return [
            'Name', 'Email', 'Phone', 'Company', 'Branch', 'Department',
            'Purpose', 'In Time', 'Out Time', 'Duration (minutes)',
        ];
    }

    public function map($v): array
    {
        $inTime  = $v->in_time  ? \Carbon\Carbon::parse($v->in_time)  : null;
        $outTime = $v->out_time ? \Carbon\Carbon::parse($v->out_time) : null;
        $duration = ($inTime && $outTime) ? $inTime->diffInMinutes($outTime) : 'N/A';

        return [
            $v->name,
            $v->email ?? 'N/A',
            $v->phone ?? 'N/A',
            $v->company->name ?? 'N/A',
            $v->branch->name ?? 'N/A',
            $v->department->name ?? 'N/A',
            $v->purpose ?? 'N/A',
            $inTime  ? $inTime->format('Y-m-d H:i:s')  : 'N/A',
            $outTime ? $outTime->format('Y-m-d H:i:s') : 'N/A',
            $duration,
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
