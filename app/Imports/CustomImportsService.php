<?php

namespace App\Imports;

use ReflectionClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class CustomImportsService implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts, SkipsEmptyRows
{
    use Importable;
    public $data;
    private $model, $validation;

    public function __construct(Model $model, array $validation=[])
    {
        $this->data = collect();
        $this->model = $model;
        $this->validation = $validation;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $result = collect();
        //undot each element if it cantain dot
        $row = collect($row)->undot();

        //loop for each record
        $row = collect($row)->map(function ($item, $key) use ($result) {
            //if value is yes no then it would exicute
            if($item == 'Yes'){
                $item = 1;
            }elseif($item == 'No'){
                $item = 0;
            }
            // fetch data for relationship column
            if(is_array($item)){
                $tableName =  collect($item)->keys()->first();
                $fieldName =  collect($item[$tableName])->keys()->first();
                $fieldValue =  $item[$tableName][$fieldName];
                //fetch id from Name of relaionship column
                // $data =  $this->model->with("$tableName:id,$fieldName")
                //         ->whereHas($tableName, function($q) use($fieldName,$fieldValue) {
                //             $q->where($fieldName,$fieldValue);
                //         })
                $data = (object)[];
                $data->id = null;
                if(!empty($fieldValue)){
                    $data =   DB::table($tableName)
                            ->where($fieldName,$fieldValue)
                            ->first();
                }
                 return $result[$key] = $data->id;
            }
            return $result[$key] = $item;
        })->all();
        return new $this->model($row);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return $this->validation;
    }
}
