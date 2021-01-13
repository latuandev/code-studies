<?php

namespace App\Imports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;

class QuestionsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Question([
            'subject_set_code' => $row['ma_de'],
            'question' => $row['cau_hoi'],
            'ans_a' => $row['dap_an_a'],
            'ans_b' => $row['dap_an_b'],
            'ans_c' => $row['dap_an_c'],
            'ans_d' => $row['dap_an_d'],
            'ans_correct' => $row['dap_an_dung'],
        ]);
    }
}
