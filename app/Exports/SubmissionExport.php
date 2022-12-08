<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SubmissionExport implements FromCollection, WithMapping, ShouldAutoSize
{
    /**
     * Submission object.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $submissions;

     /**
      * Constructs a new SubmissionExport.
      */
    public function __construct(Collection $submissions)
    {
        $this->submissions = $submissions;
    }

     /**
      * Map data.
      */
    public function map($submission): array
    {
        $data = [];

        $items = unserialize($submission->data);
        foreach ($items as $key => $value) {
            if (is_array($value) && isset($value['is_file'])) {
                $value = $value['name'];
            }

            $data[] = $value;
        }

        $data[] = $submission->updated_at->format('r');

        return $data;
    }

    /**
     * Get entire collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->submissions;
    }
}
