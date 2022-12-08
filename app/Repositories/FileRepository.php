<?php

namespace App\Repositories;

use App\File;
use Illuminate\Support\Facades\Storage;

class FileRepository
{

    /**
     * Maximum allowed file size.
     *
     * @return int
     *   Size in bytes.
     */
    public function allowedUploadSize()
    {
        // 80mb.
        return 83886080;
    }

    /**
     * Get all allowed extensions.
     *
     * @return array
     *   Array of extensions.
     */
    public function getAllowedExtensions()
    {
        return [
            'pdf',
            'doc',
            'docx',
            'jpg',
            'jpeg',
            'png',
            'bmp',
            'tiff',
            'txt',
            'odt',
            'csv',
            'ppt',
            'pptx',
            'xls',
            'xlsx'
        ];
    }

    /**
     *  Get a file.
     *
     * @param int $id
     *   File id.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get($id)
    {
        $file = File::where([
            'id' => $id,
        ])->first();

        if ($file) {
            return $file;
        }

        return null;
    }

    /**
     *  Create a new file.
     *
     * @param string $form_id
     *   Form id.
     * @param string $user_id
     *   Owner of a file.
     * @param \Illuminate\Http\UploadedFile $file
     *   File.
     *
     * @return \Illuminate\Support\Collection
     */
    public function create($form_id, $user_id, $file)
    {
        // Get unique name.
        $filename = $this->getUniqueFileName($file->getClientOriginalName(), 'temp', 'local');

        // Save the file to temp location.
        $path = $file->storeAs('temp', $filename);

        // Create a database entry.
        $file = File::create([
            'form_id' => $form_id,
            'user_id' => $user_id,
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'size' => $file->getSize(),
        ]);

        return $file;
    }

    /**
     * Moves the file from temp location to s3 bucket.
     *
     * @param \App\File $file
     *
     * @return bool
     */
    public function saveToBucket($file)
    {
        // Save it for later use.
        $tempFilePath = $file->path;
        // Get the file from temp location.
        $fileContents = Storage::disk('local')->get($file->path);

        // Generate the user directory.
        $userDirectory = "users/user_" . $file->user_id;
        $directories = Storage::disk('digitalocean')->directories('users');
        if (!in_array($userDirectory, $directories)) {
            Storage::disk('digitalocean')->makeDirectory($userDirectory);
        }

        // Generate a unique filename.
        $filename = $this->getUniqueFileName(
            str_replace('temp/', '', $file->path),
            $userDirectory,
            'digitalocean',
        );

        // Save it to the bucket.
        $bucketSave = Storage::disk('digitalocean')
            ->put($userDirectory . "/" .  $filename, $fileContents);

        // If successfull then update the File model,
        // and delete the temp file.
        if ($bucketSave) {
            $file->path = $userDirectory . "/" .  $filename;
            $file->save();

            Storage::disk('local')->delete($tempFilePath);

            return true;
        }

        return false;
    }

    /**
     *  Get a unique file name.
     *
     * @param string $file
     *   Filename with extension.
     * @param string $filepath
     *   Filepath.
     * @param string $storage
     *   Storage id.
     *
     * @return string
     */
    public function getUniqueFileName($fullName, $filepath, $storage)
    {
        $filename = pathinfo($fullName, PATHINFO_FILENAME);
        $extension = pathinfo($fullName, PATHINFO_EXTENSION);

        $uniqueName = $fullName;
        $existings = Storage::disk($storage)->exists($filepath . '/' . $fullName);
        if ($existings) {
            $unique = false;
            $i = 0;
            do {
                $newFileName = $filename . '__' . $i . '.' . $extension;
                $fileExists = Storage::disk($storage)->exists($filepath .'/' . $newFileName);
                if (!$fileExists) {
                    $unique = true;
                    $uniqueName = $newFileName;
                } else {
                    $i++;
                }
            } while ($unique != true);
        }

        return $uniqueName;
    }


    /**
     * Get all storage used by a user.
     *
     * @param id $userId
     *   User id.
     *
     * @return int
     *   Returns storage amount in megabytes.
     */
    public function getUsedStorage($userId)
    {
        $storage = 0;
        $files = File::where('user_id', $userId)->get();

        foreach ($files as $file) {
            $storage = $storage + $file->size;
        }

        // Convert bytes to megabytes.
        $storage = number_format($storage / 1048576, 1);

        return $storage;
    }
}
