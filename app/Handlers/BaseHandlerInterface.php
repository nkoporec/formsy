<?php

namespace App\Handlers;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface for handle class.
 */
interface BaseHandlerInterface
{

  /**
   * Get handler id.
   */
    public function getHandlerId();

  /**
   * Determines if the handle is active.
   */
    public function isActive();

  /**
   * Determines if the handler supports conditions.
   */
    public function hasConditions();

  /**
   * Handler settings.
   */
    public function getSettings();

  /**
   * Handler settings.
   *
   * @param id $form_id
   *   Form id.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   Request.
   *
   * @return array
   *   Returns form settings array.
   */
    public function saveSettings($form_id, Request $request);

  /**
   * Execute handler.
   */
    public function execute();

  /**
   * Convert submission tokens into real values.
   */
    public function convertTokens($submission);
}
