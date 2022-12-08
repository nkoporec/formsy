<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Submission extends Model
{
    use Searchable;

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        if (isset($array['data'])) {
            $data = unserialize($array['data']);
            foreach ($data as $name => $value) {
                $array[$name] = $value;
            }
        }

        return $array;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'form_id','user_id', 'data', 'spam',
    ];

    protected $table = "submission_data";
}
