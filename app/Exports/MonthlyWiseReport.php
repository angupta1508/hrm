<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class MonthlyWiseReport implements FromCollection, WithHeadings, WithEvents
{
    private $records;
    private $headings;
    public function __construct($records, $headings)
    {
        $this->records = $records;
        $this->headings = $headings;
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
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $mainHeading = [];
                if (!empty($this->records[0])) {
                    $mainHeading = $this->records[0];
                    if (isset($mainHeading['attendance'])) {
                        unset($mainHeading['attendance']);
                    }
                }
                $ff = count($mainHeading);

                $column_name = 'A';
                $last_key = array_key_last($mainHeading);
                foreach ($mainHeading as $key => $val) {
                    $head = ucwords(str_replace('_', ' ', $key));
                    $sheet->setCellValue($column_name . '1', $head);
                    $sheet->mergeCells($column_name . '1:' . $column_name . '2');

                    $column_index = Coordinate::columnIndexFromString($column_name);
                    $next_four_columns = range($column_index + 1, $column_index);

                    // convert column indices back to column names
                    $columns = array_map(function ($index) {
                        return Coordinate::stringFromColumnIndex($index);
                    }, $next_four_columns);
                    if (!empty($columns[0]) && $key != $last_key) {
                        $column_name = $columns[0];
                    }
                }

                foreach ($this->records[0]['attendance'] as $key => $value) {
                    $head = $key;
                    $column_index = Coordinate::columnIndexFromString($column_name);
                    $next_four_columns = range($column_index + 1, $column_index + 4);

                    // convert column indices back to column names
                    $columns = array_map(function ($index) {
                        return Coordinate::stringFromColumnIndex($index);
                    }, $next_four_columns);
                    // dd($columns );

                    $first_col = $last_col = '';
                    if (!empty($columns[0])) {
                        $first_col = $columns[0];
                        $last_col = end($columns);
                    }

                    $subHead = ['Time In', 'Time Out', 'Working Hours', 'Status'];

                    $sheet->mergeCells($first_col . '1:' . $last_col . '1');
                    $sheet->setCellValue($first_col . '1', $head);
                    foreach ($columns as $key => $column) {
                        $sheet->setCellValue($column . '2', $subHead[$key]);
                    }
                    $column_name = $last_col;
                }

                $excelArray = [];
                foreach ($this->records as $key => $atten) {
                    $excelArray[$key][] = $atten['id'];
                    $excelArray[$key][] = $atten['employee_code'];
                    $excelArray[$key][] = $atten['name'];
                    $excelArray[$key][] = $atten['hire_date'];
                    $excelArray[$key][] = $atten['department_name'];
                    foreach ($atten['attendance'] as $dd => $value) {
                        $excelArray[$key][] = !empty($value['from_time']) ? $value['from_time'] : '';
                        $excelArray[$key][] = !empty($value['to_time']) ? $value['to_time'] : '';
                        $excelArray[$key][] = !empty($value['working_hours']) ? $value['working_hours'] : '';
                        $excelArray[$key][] = !empty($value['attendance_status']) ? $value['attendance_status'] : '';
                    }
                }
                $column_name = '';
                $j = 2;
                foreach ($excelArray as $i => $value) {
                    $count = count($value);
                    $j++;
                    $t = 0;
                    if (empty($column_name)) {
                        $column_name = 'A';
                    }
                    $column_index = Coordinate::columnIndexFromString($column_name);
                    $next_four_columns = range($column_index, $column_index + $count - 1);

                    // convert column indices back to column names
                    $columns = array_map(function ($index) {
                        return Coordinate::stringFromColumnIndex($index);
                    }, $next_four_columns);
                    foreach ($value as $key => $val) {
                        $sheet->setCellValue("$columns[$key]$j", $val);
                    }
                }

            }

        ];
    }

    
}
