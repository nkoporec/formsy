<?php

namespace App\Handlers;

use App\Handlers\BaseHandler;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;

class GSheet extends BaseHandler
{
    /**
     * {@inheritdoc}
     */
    public function getHandlerId()
    {
        return parent::getHandlerId() . 'gsheet';
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        $handlerSettings = $this->getSettings();
        if (isset($handlerSettings['enabled']) && $handlerSettings['enabled'] == "on") {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $handlerSettings = $this->getSettings();

        if (!$this->validateConditionals()) {
            return;
        }

        $client = new \Google_Client();
        $client->setApplicationName(env('APP_NAME'));
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');

        $client->setAuthConfig(json_decode($handlerSettings['credentials'], true));
        $spreadsheetId = $handlerSettings['spreadsheet_id'];

        // @TODO: Handler errors.
        if (!$spreadsheetId || !$handlerSettings['spreadsheet_sheet']) {
            return false;
        }

        $service = new Google_Service_Sheets($client);

        // @TODO: Refactor and only write the actual form values.
        $formData = unserialize($this->submission->data);
        $data = [];
        foreach ($formData as $value) {
            $data[] = $value;
        }

        $body = new Google_Service_Sheets_ValueRange([
            'values' => [$data],
        ]);

        return $service->spreadsheets_values->append(
            $spreadsheetId,
            $handlerSettings['spreadsheet_sheet'],
            $body,
            ['valueInputOption' => 'RAW'],
        );
    }
}
