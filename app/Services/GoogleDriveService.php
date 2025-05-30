<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
	private $client;
	private $driverService;

	/**
	 * Construct method init setting Google client.
	 *
	 * Auth config file from storage path.
	 * Scope DRIVE_FILE
	 */
	public function __construct()
	{
		$this->client = new Client();
		$this->client->setAuthConfig(storage_path('google_credentical.json'));
		$this->client->addScope(Drive::DRIVE_FILE);
		$this->driverService = new Drive($this->client);
	}

	/**
	 * * Service method upload file to Google Drive.
	 *
	 * @param string $filePath 'path' to the file saved in the system.
	 * @param string $fileName 'name' of the file stored in the database.
	 * @param mixed $folderId 'id' of the folder you want to upload file to, default = null.
	 */
	public static function uploadFile(string $filePath, string $fileName, $folderId = null)
	{
		$self = new self();
		$fileMetadata = new DriveFile([
			'name' => $fileName,
			'parents' => [is_null($folderId) ? env('GOOGLE_DRIVE_FOLDER_ID') : $folderId]
		]);
		$content = file_get_contents($filePath);
		try {
			$file = $self->driverService->files->create($fileMetadata, [
				'data' => $content,
				'mimeType' => mime_content_type($filePath),
				'uploadType' => 'multipart',
				'fields' => 'id'
			]);
			return $file->id;
		} catch (\Exception $e) {
			return false;
		}
	}

	/**
	 * Service method to create folder in Google Drive.
	 * @param string $folderName 'name' of the folder to create in Google Drive
	 */
	public static function createFolder(string $folderName)
	{
		$self = new self();
		try {
			$fileMetadata = new DriveFile([
				'name' => $folderName,
				'mimeType' => 'application/vnd.google-apps.folder'
			]);
			$file = $self->driverService->files->create($fileMetadata, [
				'fields' => 'id'
			]);
			return $file->id;
		} catch (\Throwable $th) {
			return false;
		}
	}
}