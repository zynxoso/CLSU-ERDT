<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ManuscriptsExport implements FromCollection, WithHeadings
{
    protected $manuscripts;

    public function __construct($manuscripts)
    {
        $this->manuscripts = $manuscripts;
    }

    public function collection()
    {
        return $this->manuscripts->map(function ($manuscript) {
            return [
                'Title' => $manuscript->title,
                'Author' => $manuscript->author->name ?? '',
                'Submission Date' => $manuscript->submission_date ? $manuscript->submission_date->format('Y-m-d') : '',
                'Status' => $manuscript->status,
                'Type' => $manuscript->type,
                'Keywords' => $manuscript->keywords,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Title',
            'Author',
            'Submission Date',
            'Status',
            'Type',
            'Keywords',
        ];
    }
}
