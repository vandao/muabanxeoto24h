<?php

$path = __DIR__;
set_include_path($path . PATH_SEPARATOR . get_include_path());

class Google extends \Phalcon\Mvc\User\Component {
    private $_clientId;
    private $_clientSecret;
    private $_scopes          = array();
    private $_redirectUrl     = '';

    private $_client;
    private $_accessToken;

    private $_responseStatus  = 'error';
    private $_responseMessage = '';
    private $_responseData    = array();

    public function __construct($clientId, $clientSecret, $scopes = array(), $redirectUrl = '', $oldToken = '') {
        $this->_redirectUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        if ($clientId != '')     $this->_clientId      = $clientId;
        if ($clientSecret != '') $this->_clientSecret  = $clientSecret;


        if (count($scopes) > 0)  $this->_scopes        = $scopes;
        if ($redirectUrl != '')  $this->_redirectUrl   = $redirectUrl;
        if ($oldToken != '')     $this->_accessToken   = $oldToken;

        if ($this->_clientId == '') {
            $this->_responseMessage = 'Missing client ID';
        } elseif ($this->_clientSecret == '') {
            $this->_responseMessage = 'Missing secret';
        }

        return AjaxResponse::toArray($this->_responseStatus, $this->_responseMessage, $this->_responseData);
    }

    public function authenticate($type = 'profile', $email = '') {
        /**
         * Set client
         */
        $getScopeFunction = '_' . $type . 'Scopes';
        if (method_exists('Google', $getScopeFunction)) {
            $this->$getScopeFunction();
            $this->_getClient();

            try {
                if (isset($_GET['code'])) {
                    $this->_client->authenticate($_GET['code']);

                    $this->_accessToken = $this->_client->getAccessToken();
                }

                if ($this->_accessToken != '') {
                    $this->_client->setAccessToken($this->_accessToken);
                    $this->refreshToken();

                    $this->_responseStatus            = "success";
                } else {
                    $this->_responseStatus            = "error";
                    $this->_responseData['login_url'] = $this->_client->createAuthUrl();
                }
            } catch(Exception $e) {
                $message = $e->getMessage();
            }
        } else {
            $status  = "error";
            $message = "Scopes not existed";
        }

        return AjaxResponse::toArray($this->_responseStatus, $this->_responseMessage, $this->_responseData);
    }

    public function getUser() {
        try {
            $oauth  = new Google_Service_Oauth2($this->_client);

            $status = 'success';
            $userProfile = $oauth->userinfo->get();

            $this->_responseData = array(
                'email'         => $userProfile['email'],
                'first_name'    => $userProfile['givenName'],
                'last_name'     => $userProfile['familyName'],
                'gender'        => $userProfile['gender'],
                'identity'      => $userProfile['id'],
                'token'         => $this->_accessToken,
                'date_of_birth' => $userProfile['birthday'],
                'avatar'        => $userProfile['picture']
            );
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        return AjaxResponse::toArray($this->_responseStatus, $this->_responseMessage, $this->_responseData);
    }





    public function uploadDriveFile($filePath, $parentId = '', $filename = '', $description = '') {
        $status  = "error";
        $message = "";
        $data    = array();

        $service = new Google_Service_Drive($this->_client);

        /************************************************
        If we're signed in then lets try to upload our
        file.
        ************************************************/
        try {
            if ($this->_client->getAccessToken()) {
                if ($filename == '') $filename = pathinfo($filePath, PATHINFO_BASENAME);
                $chunkSizeBytes    = 1 * 1024 * 1024;

                $file              = new Google_Service_Drive_DriveFile();
                $file->title       = $filename;
                $file->description = $description;

                // Set the parent folder.
                if ($parentId != '') {
                    $parent       = new Google_Service_Drive_ParentReference();
                    $parent->id   = $parentId;

                    $file->setParents(array($parent));
                }

                // Call the API with the media upload, defer so it doesn't immediately return.
                $this->_client->setDefer(true);
                $request = $service->files->insert($file);

                // Create a media file upload to represent our upload process.
                $media = new Google_Http_MediaFileUpload(
                    $this->_client,
                    $request,
                    mime_content_type($filePath),
                    null,
                    true,
                    $chunkSizeBytes
                );
                $media->setFileSize(filesize($filePath));

                // Upload the various chunks. $status will be false until the process is
                // complete.
                $status = false;
                $handle = fopen($filePath, "rb");
                while (!$status && !feof($handle)) {
                    $chunk  = fread($handle, $chunkSizeBytes);
                    $status = $media->nextChunk($chunk);
                }

                // The final value of $status will be the data from the API for the object
                // that has been uploaded.
                if ($status != false) {
                    $data   = $status;
                    $status = 'success';
                }

                fclose($handle);
                // Reset to the client to execute requests immediately in the future.
                $this->_client->setDefer(false);
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        return AjaxResponse::toArray($status, $message, $data);
    }

    public function deleteDriveFile($fileId) {
        $status   = "error";
        $message  = "";
        $data     = array();

        try {
            $service  = new Google_Service_Drive($this->_client);

            $response = $service->files->delete($fileId);

            $status   = 'success';
        } catch (Exception $e) {
            $message  = $e->getMessage();
        }

        return AjaxResponse::toArray($status, $message, $data);
    }

    public function downloadDriveFile($fileId) {
        $status   = "error";
        $message  = "";
        $data     = array();

        try {
            $service  = new Google_Service_Drive($this->_client);

            $file     = $service->files->get($fileId);
            $title    = $file->getTitle();

            $downloadUrl = $file->getDownloadUrl();
            // $downloadUrl = str_replace('&gd=true', '', $downloadUrl);

            if ($downloadUrl) {
                $request         = new Google_Http_Request($downloadUrl, 'GET', null, null);
                $signHttpRequest = $this->_client->getAuth()->sign($request);
                $httpRequest     = $this->_client->getIo()->makeRequest($signHttpRequest);

                if ($httpRequest->getResponseHttpCode() == 200) {
                    $status          = 'success';
                    $data['content'] = $httpRequest->getResponseBody();
                } else {
                    $message = "An error occurred.";
                }
            } else {
                $message = "The file doesn't have any content stored on Drive.";
            }
        } catch (Exception $e) {
            $message  = $e->getMessage();
        }

        return AjaxResponse::toArray($status, $message, $data);
    }

    public function getDriveFile($fileId) {
        $status   = "error";
        $message  = "";
        $data     = array();

        try {
            $service = new Google_Service_Drive($this->_client);
            $file    = $service->files->get($fileId);

            $status  = "success";
            $data    = $file;
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        return AjaxResponse::toArray($status, $message, $data);
    }

    public function createDriveFolder($folderName, $parentId = '') {
        $status   = "error";
        $message  = "";
        $data     = array();

        try {
            $service        = new Google_Service_Drive($this->_client);

            $file           = new Google_Service_Drive_DriveFile();
            $file->title    = $folderName;
            $file->mimeType = 'application/vnd.google-apps.folder';

            // Set the parent folder.
            if ($parentId != '') {
                $parent       = new Google_Service_Drive_ParentReference();
                $parent->id   = $parentId;

                $file->setParents(array($parent));
            }

            $status  = 'success';
            $data    = $service->files->insert($file);
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        return AjaxResponse::toArray($status, $message, $data);
    }

    public function uploadYoutube($filePath, $title, $categoryId) {
        $status   = "error";
        $message  = "";
        $data     = array();
        
        try {
            // Create a snippet with title, description, tags and category ID
            // Create an asset resource and set its snippet metadata and type.
            // This example sets the video's title, description, keyword tags, and
            // video category.
            $snippet = new Google_Service_YouTube_VideoSnippet();
            $snippet->setTitle($title);
//                    $snippet->setDescription($class->Description);
//                    $snippet->setTags(array("tag1", "tag2"));

            // Numeric video category. See
            // https://developers.google.com/youtube/v3/docs/videoCategories/list
            $snippet->setCategoryId($categoryId);

            // Set the video's status to "public". Valid statuses are "public",
            // "private" and "unlisted".
            $status = new Google_Service_YouTube_VideoStatus();
            $status->privacyStatus = "public";

            // Associate the snippet and status objects with a new video resource.
            $video = new Google_Service_YouTube_Video();
            $video->setSnippet($snippet);
            $video->setStatus($status);

            // Specify the size of each chunk of data, in bytes. Set a higher value for
            // reliable connection as fewer chunks lead to faster uploads. Set a lower
            // value for better recovery on less reliable connections.
            $chunkSizeBytes = 1 * 1024 * 1024;

            // Setting the defer flag to true tells the client to return a request which can be called
            // with ->execute(); instead of making the API call immediately.
            $client->setDefer(true);

            // Create a request for the API's videos.insert method to create and upload the video.
            $insertRequest = $youtube->videos->insert("status,snippet", $video);

            // Create a MediaFileUpload object for resumable uploads.
            $media = new Google_Http_MediaFileUpload(
                $client,
                $insertRequest,
                'video/*',
                null,
                true,
                $chunkSizeBytes
            );
            $media->setFileSize(filesize($videoPath));


            // Read the media file and upload it chunk by chunk.
            $result = false;
            $handle = fopen($videoPath, "rb");
            while (!$status && !feof($handle)) {
                $chunk  = fread($handle, $chunkSizeBytes);
                $result = $media->nextChunk($chunk);
            }

            fclose($handle);

            // If you want to make other calls after the file upload, set setDefer back to false
            $client->setDefer(false);

            $data   = $status;
            $status = 'success';
        } catch (Google_ServiceException $e) {
            $message = $e->getMessage();
        } catch (Google_Exception $e) {
            $message = $e->getMessage();
        }

        return AjaxResponse::toArray($status, $message, $data);
    }

    public function _getClient() {
        $this->_client = new Google_Client();
        $this->_client->setAccessType('offline');
        $this->_client->setState('offline');
        $this->_client->setClientId($this->_clientId);
        $this->_client->setClientSecret($this->_clientSecret);
        $this->_client->setRedirectUri($this->_redirectUrl);

        $this->_client->setScopes($this->_scopes);
    }

    private function _profileScopes() {
        $scopes = array(
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile'
        );

        $this->_scopes = array_merge($this->_scopes, $scopes);
    }

    private function _youtubeScopes() {
        $scopes = array(
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
            'https://www.googleapis.com/auth/youtube'
        );

        $this->_scopes = array_merge($this->_scopes, $scopes);
    }

    private function _driveScopes() {
        $scopes = array(
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
            'https://www.googleapis.com/auth/drive',
            'https://www.googleapis.com/auth/drive.file',
        );

        $this->_scopes = array_merge($this->_scopes, $scopes);
    }

    public function refreshToken() {
        if ($this->_client->isAccessTokenExpired()) {
            $accessToken = $this->_client->getAccessToken();
            $googleToken = json_decode($accessToken);

            $this->_client->refreshToken($googleToken->refresh_token);

            $this->_accessToken = $this->_client->getAccessToken();
        }
    }
}