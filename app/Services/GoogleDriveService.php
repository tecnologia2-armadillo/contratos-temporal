<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    protected $client;
    protected $driveService;

    public function __construct()
    {
        $this->client = new Client();
        
        // Path to the service account JSON key file
        $credentialsPath = base_path(env('GOOGLE_DRIVE_CREDENTIALS_PATH', 'google-credentials.json'));
        
        if (file_exists($credentialsPath)) {
            $this->client->setAuthConfig($credentialsPath);
            $this->client->addScope(Drive::DRIVE_FILE);
            $this->driveService = new Drive($this->client);
        } else {
            Log::error("Google Drive credentials file not found at: " . $credentialsPath);
        }
    }

    /**
     * Upload a file to Google Drive.
     *
     * @param string $fileContent Local path or content
     * @param string $fileName Name for the file in Drive
     * @param string|null $folderId Target folder ID
     * @return array Web view link and id
     */
    public function uploadFile($fileContent, $fileName, $folderId = null)
    {
        if (!$this->driveService) {
            throw new \Exception("Google Drive Service semi-initialized (missing credentials file?)");
        }

        $folderId = $folderId ?: env('GOOGLE_DRIVE_FOLDER_ID');
        
        $fileMetadata = new DriveFile([
            'name' => $fileName,
            'parents' => $folderId ? [$folderId] : []
        ]);

        $file = $this->driveService->files->create($fileMetadata, [
            'data' => $fileContent,
            'mimeType' => 'application/pdf',
            'uploadType' => 'multipart',
            'fields' => 'id, webViewLink',
            'supportsAllDrives' => true,
            'supportsTeamDrives' => true,
        ]);

        return [
            'id' => $file->id,
            'link' => $file->webViewLink
        ];
    }
}
