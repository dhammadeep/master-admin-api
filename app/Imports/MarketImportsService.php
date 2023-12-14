<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MarketImportsService implements ToCollection, WithValidation, WithHeadingRow, WithStartRow
{
    use Importable;
    public $data;
    private $model, $validation;

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    public function __construct(Model $model, array $validation=[])
    {
        $this->data = collect();
        $this->model = $model;
        $this->validation = $validation;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $relationshipModel = 'location';
            $location_id = $this->model->$relationshipModel()->create([
                'address' => $row['address'],
                'pincode' => $row['pincode']
            ])->id;
            $this->model::create([
                'name' => $row['name'],
                'location_id'=> $location_id
            ]);
        }
    }

    public function rules(): array
    {
        return $this->validation;
    }
}
