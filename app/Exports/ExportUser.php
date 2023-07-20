<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportUser implements FromCollection, WithHeadings, WithEvents
{
    private $records;
    private $headings;
    public function __construct($records, $headings, $header = [])
    {
        $this->records = $records;
        $this->headings = $headings;
        $this->header = $header;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->records);
    }

    public function registerEvents(): array
    {
        // dd($this->header);
        return [
            // Handle by a closure.
            AfterSheet::class => function (AfterSheet $event) {

                // last column as letter value (e.g., D)
                $headierCount = count($this->header);
                $last_column = Coordinate::stringFromColumnIndex(count($this->headings));
                // dd($last_column);
                $i = 1;
                $event->sheet->insertNewRowBefore(1, $headierCount+1);
                foreach ($this->header as $key => $val) {
                    $event->sheet->mergeCells('A' . $i . ':' . $last_column . '1');
                    $event->sheet->setCellValue('A' . $i, $key . ' : ' . $val);
                    $i++;
                }
            },
        ];
    }
    
}
