<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ErrorExport implements FromCollection, WithHeadings
{
    private  $isError, $errors, $data, $headings;
    use Exportable;
    public function __construct($errors = [], $data = [])
    {
        $this->errors = $errors;
        $this->data = $data;
        $this->headings = collect($this->data)->first()->first();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $errorCollection = collect($this->errors);
        $dataCollection = collect($this->data);
        $groupError = $errorCollection->mapToGroups(function ($item, $key) {
            return [$item->row() => $item->errors()];
        });

        $dataCollection = $dataCollection->first()->map(function ($dataItem, $key) use ($groupError) {
            $item = collect();
            // if key is numeric then remove
            $dataItem->map(function ($val, $k) use ($item) {
                if(!is_int($k)){
                    $item[$k]=$val;
                }
            });
            $rowKey = $key + 2;
            if (isset($groupError[$rowKey])) {
                $item['ErrorComment'] = ($groupError[$rowKey]->flatten()->count() > 1)?$groupError[$rowKey]->flatten()->implode(','):$groupError[$rowKey]->flatten()->first();
            }
            return $item;
        });
        // dd($dataCollection);
        return $dataCollection;
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        //Remove numeric extra element if exist from collection
        $this->headings = $this->headings->reject(function ($item, $key) {
            return is_numeric($key);
        });
        $this->headings = $this->headings->keys()->push('ErrorComment')->all();
        return $this->headings;
    }
}
