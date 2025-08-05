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

    // pag-convert ng manuscripts data para sa excel export
    public function collection()
    {
        return $this->manuscripts->map(function ($manuscript) {
            return [
                'Title' => $manuscript->title,
                'Author' => $manuscript->author->name ?? '', // kunin ang pangalan ng author o blank kung walang author
                'Submission Date' => $manuscript->submission_date ? $manuscript->submission_date->format('Y-m-d') : '', // i-format ang date o blank kung walang date
                'Status' => $manuscript->status,
                'Type' => $manuscript->type,
                'Keywords' => $manuscript->keywords,
            ];
        });
    }

    // mga header names para sa excel file
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
