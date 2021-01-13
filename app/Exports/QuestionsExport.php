<?php

namespace App\Exports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;

class QuestionsExport implements FromQuery
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($code)
    {
        $this->code = $code;
    }
    public function query()
    {
        return Question::query()->where('subject_set_code', $this->code);
    }
}
