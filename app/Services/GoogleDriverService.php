<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Log;

class GoogleDriverService
{
    // Google Drive Folder ID (Get from folder URL on Google Drive)
    const FOLDER_ID = '1uXBDTzsiHe6SxknhUnw35up0_RUf8Kve';

    // Init google client
    private $client;

    /**
     * Construct method init setting Google client.
     *
     * Auth config file from storage path.
     * Scope DRIVE_FILE
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('vibra-driver.json'));
        $this->client->addScope(Drive::DRIVE_FILE);
    }

    /**
     * Service method synchronize to Google Driver.
     *
     * @param string $filePath path to the file saved in the system.
     * @param string $fileName name of the file stored in the database.
     * @return int|mixed id of file sync.
     */
    public function synchronize($filePath, $fileName)
    {
        // Init Driver client.
        $driverService = new Drive($this->client);
        // Init file meta data.
        $fileMetadata = new DriveFile([
            'name' => $fileName,
            'parents' => [self::FOLDER_ID] // The folder where the specified file is saved.
        ]);
        // Read mime file type.
        $content = file_get_contents($filePath);
        try {
            // Sync file to Google driver.
            $sync = $driverService->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => mime_content_type($filePath),
                'uploadType' => 'multipart'
            ]);
            return $sync->id;
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }
}