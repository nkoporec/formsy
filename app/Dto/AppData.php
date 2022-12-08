<?php

namespace App\Dto;

use App\Repositories\AppSettingsRepository;
use Spatie\DataTransferObject\DataTransferObject;
use Symfony\Component\HttpFoundation\Request;

class AppData extends DataTransferObject
{

    /** @var string */
    public $appId;

    /** @var string */
    public $formId;

    /** @var string|bool */
    public $redirectUrl;

    /** @var string */
    public $name;

    /**
     * Generate data.
     *
     * @param array $data
     *   Form data.
     * @param Symfony\Component\HttpFoundation\Request $request
     *   Request object.
     *
     * @return array
     *   Returns array of data.
     */
    public static function fromData($data, Request $request): self
    {
        return new self([
            'appId' => $data['app-id'],
            'name' => isset($data['form-name']) ? $data['form-id'] : $data['form-id'],
            'formId' => self::generateFormMachineName($data['form-id'], $data['app-id']),
            'redirectUrl' => isset($data['redirect-url']) ? self::getRedirectUrl($data['redirect-url'], $request) : false,
        ]);
    }

    /**
     * Generate form machine name.
     *
     * @param string $name
     *   Form name.
     * @param string $appId
     *   App id.
     *
     * @return string
     *   Returns formatted string.
     */
    protected static function generateFormMachineName($name, $appId)
    {
        // @TODO: Remove special characters as well.
        $name = str_replace(' ', '-', $name);
        $name = preg_replace('/_/', '-', $name);

        $appSettings = new AppSettingsRepository();
        $userId = $appSettings->getUserByKey($appId);

        $name = preg_replace('/-+/', '-', $name);
        $name = $userId . "_" . $name;

        return $name;
    }

    /**
     * Get redirect url.
     *
     * @param string $redirectUrl
     *   Redirect url.
     * @param string $referer
     *   Referer url.
     *
     * @return bool|string
     *   Returns url string on success.
     */
    protected static function getRedirectUrl($redirectUrl, $referer)
    {
        $redirectUrl = parse_url($redirectUrl);
        // If we don't have the path, just redirect back.
        if (!isset($redirectUrl['path'])) {
            return false;
        }

        // If we have a relative URL, then build the full path using referer value.
        if (isset($redirectUrl['scheme']) == false || isset($redirectUrl['host']) == false) {
            $parsedReferer = parse_url($referer);

            // If we don't have enough data, just redirect back.
            if (!isset($parsedReferer['scheme']) || !isset($parsedReferer['host'])) {
                return false;
            }

            if (isset($parsedReferer['port'])) {
                $rootReferer = $parsedReferer['scheme'] . '://' . $parsedReferer['host'] . ':'. $parsedReferer['port'];
            } else {
                $rootReferer = $parsedReferer['scheme'] . '://' . $parsedReferer['host'];
            }

            $redirectUrl = $rootReferer . "" . $redirectUrl['path'];

            return $redirectUrl;
        }
    }
}
