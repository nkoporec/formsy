<?php

namespace App\Services;

use App\Form;
use App\Repositories\FormRepository;
use App\Repositories\SubmissionRepository;

class FormHandlerDiscovery
{
    /**
     * Handler directory path.
     *
     * @var string
     */
    protected $handlerPath;

    /**
     * Handler namespace.
     *
     * @var string
     */
    protected $handlerNamespace;

    /**
     *  Form repository.
     *
     * @var \App\Repositories\FormRepository
     */
    protected $formRepository;

    /**
     * Submission Repository.
     *
     * @var \App\Repositories\SubmissionRepository
     */
    protected $submissionRepository;

    /**
     * Create a new FormHandlerDiscovery instance.
     */
    public function __construct()
    {
        $this->handlerPath = app_path() . '/Handlers';
        $this->handlerNamespace = 'App\Handlers';
        $this->submissionRepository = new SubmissionRepository();
        $this->formRepository = new FormRepository($this->submissionRepository);
    }

    /**
     * Get all available handlers.
     *
     * @param \App\Models\Form $form
     *   Form model.
     * @param bool $enabled
     *   If true then only enabled handler will be returned.
     *
     * @return array
     *   Array of handlers.
     */
    public function getHandlers(Form $form, $enabled = false)
    {
        $handlerFiles = $this->scanHandlerDirectory();
        $handlerSettings = $this->formRepository
            ->getOption($form->id, 'handlers_settings');

        // Get names out of filepaths.
        $handlers = [];
        foreach ($handlerFiles as $filepath) {
            $filename = explode('/', $filepath);
            $handlerClass = $this->handlerNamespace . "\\" . last($filename);
            $handlerId = strtolower(last($filename));
            $handlerEnabled = isset($handlerSettings[$handlerId]) ? $handlerSettings[$handlerId] : false;
            if ($enabled && $handlerEnabled) {
                $handlers[$handlerId] = $handlerClass;
                continue;
            }

            $handlers[$handlerId] = $handlerClass;
        }

        return $handlers;
    }

    /**
     * Scan the handler directory.
     *
     * @param string $path
     *   Path to handler directory.
     *
     * @return array
     *   Return array of handler paths.
     *
     */
    protected function scanHandlerDirectory($path = null)
    {
        $handlersFiles = [];
        if (!$path) {
            $path = $this->handlerPath;
        }

        $handlersDir = scandir($path);
        foreach ($handlersDir as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            };

            $filename = $path . '/' . $item;
            if (is_dir($filename)) {
                $handlersFiles = array_merge(
                    $handlersFiles,
                    $this->scanHandlerDirectory($filename)
                );
            } else {
                if (strpos($filename, 'BaseHandler') !== false) {
                    continue;
                }
                $handlersFiles[] = substr($filename, 0, -4);
            }
        }

        return $handlersFiles;
    }
}
