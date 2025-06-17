<?php 

namespace App\Exports;

use App\Models\Visitor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VisitorsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Visitor::select('name', 'email', 'phone', 'purpose', 'status', 'in_time', 'out_time', 'created_at')->get();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Phone',
            'Purpose',
            'Status',
            'In Time',
            'Out Time',
            'Registered At',
        ];
    }
}
