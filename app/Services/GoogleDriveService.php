<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Helpers\FileHelper;
use Google\Client;
use Google\Http\MediaFileUpload;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Storage;

class GoogleDriveService
{
    private $client;

    private $driveService;

    /**
     * Construct method init setting Google client.
     *
     * Auth config file from storage path.
     * Scope DRIVE
     */
    public function __construct()
    {
        $this->client = new Client;
        $this->client->setAuthConfig(storage_path(env('GOOGLE_DRIVE_CREDENTICALS')));
        $this->client->addScope(Drive::DRIVE);
        $this->driveService = new Drive($this->client);
    }

    /**
     * * Service method for upload file to Google Drive.
     *
     * @param string $filePath 'path' to the file saved in the system.
     * @param string $fileName 'name' of the file stored in the database.
     * @param mixed $folderId 'id' of the folder you want to upload file to, default = null.
     */
    public static function uploadSmallFile(string $filePath, string $fileName, $folderId = null)
    {
        $self = new self;
        $fileMetadata = new DriveFile([
            'name'    => $fileName,
            'parents' => [is_null($folderId) ? env('GOOGLE_DRIVE_ROOT_FOLDER_ID') : $folderId]
        ]);
        $content = file_get_contents($filePath);
        try {
            $file = $self->driveService->files->create($fileMetadata, [
                'data'       => $content,
                'mimeType'   => mime_content_type($filePath),
                'uploadType' => 'multipart',
                'fields'     => 'id'
            ]);

            return $file->id;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Support method for upload big file to Google Drive
     *
     * @param string $filePath
     * @param string $fileName
     * @param mixed $folderId
     * @return bool
     */
    public static function chunkFileUpload(string $filePath, string $fileName, $folderId = null)
    {
        $self = new self;
        try {
            $fileMetadata = new DriveFile([
                'name'    => $fileName,
                'parents' => [is_null($folderId) ? env('GOOGLE_DRIVE_ROOT_FOLDER_ID') : $folderId]
            ]);
            $chunkSizeBytes = 1 * 1024 * 1024;
            $self->client->setDefer(true);
            $request = $self->driveService->files->create($fileMetadata);
            $mimeType = mime_content_type($filePath);
            $content = file_get_contents($filePath);

            $media = new MediaFileUpload(
                $self->client,
                $request,
                $mimeType,
                $content,
                true,
                $chunkSizeBytes
            );
            $media->setFileSize(filesize($filePath));
            $status = false;
            $handle = fopen($filePath, 'rb');
            while (!$status && !feof($handle)) {
                $chunk = FileHelper::readFileChunk($handle, $chunkSizeBytes);
                $status = $media->nextChunk($chunk);
            }
            $result = false;
            if ($status != false) {
                $result = $status;
            }
            fclose($handle);

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Sync uploaded avatar, song, lyrics and thumbnail to Google drive
     *
     * @param string $type
     * @param mixed $path
     * @param mixed $userName
     * @param mixed $fileName
     * @return bool|mixed|\Illuminate\Http\JsonResponse
     */
    public static function syncFileToDrive(string $type, $path, $userName, $fileName)
    {
        try {
            $filePath = Storage::disk('public-api')->path($path);
            if (!file_exists($filePath)) {
                return ApiResponse::dataNotfound();
            }

            $userFolder = self::findFolderByName($userName);
            if (!$userFolder) {
                $userFolderId = self::createFolder($userName);
            } else {
                $userFolderId = is_object($userFolder) ? $userFolder->getId() : $userFolder['id'];
            }

            if (!$userFolderId) {
                return ApiResponse::internalServerError();
            }

            $typeFolder = self::findFolderByName($type, $userFolderId);
            if (!$typeFolder) {
                $typeFolderId = self::createFolder($type, $userFolderId);
            } else {
                $typeFolderId = is_object($typeFolder) ? $typeFolder->getId() : $typeFolder['id'];
            }

            if (!$typeFolderId) {
                return ApiResponse::internalServerError();
            }

            // $fileName = substr($user->avatar, 1);
            if (filesize($filePath) >= 5 * 1024 * 1024) {
                self::chunkFileUpload($filePath, $fileName, $typeFolderId);
            } else {
                self::uploadSmallFile($filePath, $fileName, $typeFolderId);
            }

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Service method to create folder in Google Drive.
     *
     * @param string $folderName 'name' of the folder to create in Google Drive
     */
    public static function createFolder(string $folderName, $folderId = null)
    {
        $self = new self;
        try {
            $folderMetadata = new DriveFile([
                'name'     => $folderName,
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents'  => [is_null($folderId) ? env('GOOGLE_DRIVE_ROOT_FOLDER_ID') : $folderId]
            ]);
            $folder = $self->driveService->files->create($folderMetadata, [
                'fields' => 'id'
            ]);

            return $folder->id;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Support method find folder in google drive by name
     *
     * @param string $name
     * @param mixed $parentId
     * @return object|null
     */
    public static function findFolderByName(string $name, $parentId = null)
    {
        try {
            $self = new self;

            $query = "mimeType='application/vnd.google-apps.folder' and name = '{$name}'";
            if (!is_null($parentId)) {
                $query .= " and '{$parentId}' in parents";
            }

            $response = $self->driveService->files->listFiles([
                'q'        => $query,
                'spaces'   => 'drive',
                'fields'   => 'files(id, name)',
                'pageSize' => 1,
            ]);

            $folders = $response->getFiles();

            if (count($folders) > 0) {
                $folder = $folders[0];

                // Kiểm tra chắc chắn có ID
                if (is_object($folder) && method_exists($folder, 'getId')) {
                    return $folder;
                }

                if (is_array($folder) && isset($folder['id'])) {
                    return (object) $folder;
                }
            }

            return null;

        } catch (\Throwable $th) {
            echo 'Error: ' . $th->getMessage();

            return null;
        }
    }
}
