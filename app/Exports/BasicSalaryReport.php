<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class BasicSalaryReport implements FromCollection, WithHeadings, WithEvents
{
    private $records;
    private $headings;
    private $header;
    private $other;
    public function __construct($records, $headings, $header = [],$other=[])
    {
        $this->records = $records;
        $this->headings = $headings;
        $this->header = $header;
        $this->other = $other;
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
                $lastRow = $event->sheet->getDelegate()->getHighestRow();
                // dd($this->other);

                $l = $lastRow+1;
                $event->sheet->getDelegate()->setCellValue("L{$l}", 'Total  :  '.$this->other['total_basic_salary']);
                $event->sheet->getDelegate()->setCellValue("AA{$l}", 'Total  :  '.$this->other['total_net_salary']);
                
                
                // $sumFormula = "=SUM(L2:L{$lastRow})";
                // $l = $lastRow+1;
                // $event->sheet->getDelegate()->setCellValue("L{$l}", $sumFormula);
                // $event->sheet->getDelegate()->getStyle("L{$l}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            
            },
        ];
    }
    
}
