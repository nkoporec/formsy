<?php

namespace App\Dto;

use Spatie\DataTransferObject\DataTransferObject;
use Illuminate\Http\Request;
use App\Dto\AppData;

class FormData extends DataTransferObject
{
    const INPUT_PREFIX = "_formsy_";

    /** @var \App\Dto\AppData */
    public $appData;

    /** @var array */
    public $submissionData;

    /**
     * Generate data.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     *   Request object.
     *
     * @return array
     *   Returns array of data.
     */
    public static function fromRequest(Request $request): self
    {
        return new self([
            'appData' => AppData::fromData(self::getAppDataInputs($request), $request),
            'submissionData' => self::submissionData($request),
        ]);
    }

    /**
     * Get app data.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     *   Request object.
     *
     * @return array
     *   Returns array of data.
     */
    protected static function getAppDataInputs(Request $request)
    {
        $appDataInputs = [];
        // @TODO: Make it prettier, see
        // https://stackoverflow.com/questions/4260086/php-how-to-use-array-filter-to-filter-array-keys
        foreach ($request->all() as $key => $value) {
            if (strpos($key, self::INPUT_PREFIX) !== false) {
                $keyWithoutPrefix = str_replace(self::INPUT_PREFIX, '', $key);
                $appDataInputs[$keyWithoutPrefix] = $value;
            }
        }

        return $appDataInputs;
    }

    /**
     * Get submission related data.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     *   Request object.
     *
     * @return array
     *   Returns array of data.
     */
    protected static function submissionData(Request $request)
    {
        // @TODO: Refactor this.
        $submissionInputs = [];
        foreach ($request->all() as $key => $value) {
            if (strpos($key, self::INPUT_PREFIX) !== false || $key == "q") {
                continue;
            }

            $submissionInputs[$key] = $value;
        }

        return $submissionInputs;
    }
}
