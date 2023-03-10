<?php

namespace WilokeListingTools\Framework\Helpers;

use WilokeListingTools\Frontend\User;

class FileSystem
{
    private static function focusDisableLog()
    {
        if (defined('FOCUS_DISABLE_LOG') && FOCUS_DISABLE_LOG) {
            return true;
        }

        return false;
    }

    public static function writeLog($log, $isFocus = false)
    {
        if (true === WP_DEBUG || $isFocus) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }

    public static function isWilcityFolderExisted($subFolder = '')
    {
        $aUploadDir = wp_upload_dir();
        $folder = empty($subFolder) ? $aUploadDir['basedir'] . '/' . WILCITY_WHITE_LABEL :
            $aUploadDir['basedir'] . '/' . WILCITY_WHITE_LABEL . '/' . $subFolder;

        return is_dir($folder);
    }

    private static function buildFileDir($fileName, $subFolder = '')
    {
        $aUploadDir = wp_upload_dir();
        if (!empty($subFolder)) {
            $fileDir = $aUploadDir['basedir'] . '/' . WILCITY_WHITE_LABEL . '/' . $subFolder . '/' . $fileName;
        } else {
            $fileDir = $aUploadDir['basedir'] . '/' . WILCITY_WHITE_LABEL . '/' . $fileName;
        }

        return $fileDir;
    }

    public static function createWilcityFolder()
    {
        if (self::focusDisableLog()) {
            return false;
        }

        if (self::isWilcityFolderExisted()) {
            return true;
        }
        $aUploadDir = wp_upload_dir();
        if (wp_mkdir_p($aUploadDir['basedir'] . '/' . WILCITY_WHITE_LABEL)) {
            return true;
        }

        return false;
    }

    public static function getWilcityFolderDir()
    {
        self::createWilcityFolder();
        $aUploadDir = wp_upload_dir();

        return $aUploadDir['basedir'] . '/' . WILCITY_WHITE_LABEL . '/';
    }

    public static function createUserFolder($userID)
    {
        if (self::focusDisableLog()) {
            return false;
        }

        self::createWilcityFolder();

        $userFolder = User::getField('user_login', $userID);

        if (self::isWilcityFolderExisted($userFolder)) {
            return true;
        }

        $aUploadDir = wp_upload_dir();
        if (wp_mkdir_p($aUploadDir['basedir'] . '/' . WILCITY_WHITE_LABEL . '/' . $userFolder)) {
            return true;
        }

        return false;
    }

    public static function getUserFolderUrl($userID)
    {
        self::createUserFolder($userID);

        $userFolder = User::getField('user_login', $userID);
        $aUploadDir = wp_upload_dir();

        return $aUploadDir['baseurl'] . '/' . WILCITY_WHITE_LABEL . '/' . $userFolder . '/';
    }

    public static function getUserFolderDir($userID)
    {
        self::createUserFolder($userID);

        $userFolder = User::getField('user_login', $userID);
        $aUploadDir = wp_upload_dir();

        return $aUploadDir['basedir'] . '/' . WILCITY_WHITE_LABEL . '/' . $userFolder . '/';
    }

    public static function createSubFolder($subFolder)
    {
        self::createWilcityFolder();
        if (self::isWilcityFolderExisted($subFolder)) {
            return true;
        }

        $aUploadDir = wp_upload_dir();
        if (wp_mkdir_p($aUploadDir['basedir'] . '/' . WILCITY_WHITE_LABEL . '/' . $subFolder)) {
            return true;
        }

        return false;
    }

    public static function getWilcityFolderUrl()
    {
        self::createWilcityFolder();
        $aUploadDir = wp_upload_dir();

        return $aUploadDir['baseurl'] . '/' . WILCITY_WHITE_LABEL . '/';
    }

    public static function getFileURI($fileName = '', $subFolder = '')
    {
        $aUploadDir = wp_upload_dir();

        if (!empty($subFolder)) {
            return $aUploadDir['baseurl'] . '/' . WILCITY_WHITE_LABEL . '/' . $subFolder . '/' . $fileName;
        } else {
            return $aUploadDir['baseurl'] . '/' . WILCITY_WHITE_LABEL . '/' . $fileName;
        }
    }

    public static function getFileDir($fileName = '', $subFolder = '')
    {
        return self::buildFileDir($fileName, $subFolder);
    }

    public static function deleteFile($fileName, $subFolder = '')
    {
        $fileDir = self::buildFileDir($fileName, $subFolder);
        if (file_exists($fileDir)) {
            wp_delete_file($fileDir);
        }
    }

    public static function createFile($fileName = '', $subFolder = '')
    {
        if (!self::createWilcityFolder()) {
            return false;
        }

        if (!empty($subFolder)) {
            if (!self::createSubFolder($subFolder)) {
                return false;
            }
        }

        $fileDir = self::buildFileDir($fileName, $subFolder);

        if (!function_exists('WP_Filesystem')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        WP_Filesystem();
        global $wp_filesystem;

        return $wp_filesystem->put_contents(
            $fileDir,
            '',
            FS_CHMOD_FILE // predefined mode settings for WP files
        );
    }

    public static function isFileExists($fileName, $subFolder = '')
    {
        $fileDir = self::buildFileDir($fileName, $subFolder);

        return file_exists($fileDir);
    }

    public static function filePutContents($fileName, $text, $subFolder = '')
    {
        if (self::focusDisableLog()) {
            return false;
        }

        $fileDir = self::buildFileDir($fileName, $subFolder);
        if (!self::isFileExists($fileName, $subFolder)) {
            if (!self::createFile($fileName, $subFolder)) {
                return false;
            }
        }

        if (!function_exists('WP_Filesystem')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        WP_Filesystem();
        global $wp_filesystem;

        return $wp_filesystem->put_contents($fileDir, $text, FS_CHMOD_FILE);
    }

    private static function fileUpdateContent($fileName, $text, $subFolder = '', $isAppend = false)
    {
        if (self::focusDisableLog()) {
            return false;
        }

        $fileDir = self::buildFileDir($fileName, $subFolder);

        if (!self::isFileExists($fileName)) {
            if (!self::createFile($fileName)) {
                return self::createFile($fileName);
            }
        }

        if (!function_exists('WP_Filesystem')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        WP_Filesystem();
        global $wp_filesystem;
        $originalText = $wp_filesystem->get_contents($fileDir);

        if ($isAppend) {
            $text = $text . '--' . $originalText;
        } else {
            $text = $originalText . '--' . $text;
        }

        return $wp_filesystem->put_contents($fileDir, $text, FS_CHMOD_FILE);
    }

    private static function clearLog($text)
    {
        $text = is_array($text) ? json_encode($text) : $text;

        return "\r\n" . date('m-d-Y h:i:s', current_time('timestamp', 1)) . ': ' . $text . "\r\n";
    }

    public static function logPayment($fileName, $text, $isFirstStep = false)
    {
        $status = GetWilokeSubmission::getField('toggle_debug');
        if ($status == 'enable') {
            if ($isFirstStep) {
                self::filePutContents($fileName, self::clearLog($text));
            } else {
                self::fileUpdateContent($fileName, self::clearLog($text));
            }
        }
    }

    public static function logPaymentError($fileName, $text)
    {
        if (self::focusDisableLog()) {
            return false;
        }

        self::fileUpdateContent($fileName, self::clearLog($text));
    }

    public static function logError($text, $class = '', $method = '', $isCleanUpBeforeData = false)
    {
        if (self::focusDisableLog()) {
            return false;
        }

        if (!empty($class)) {
            $text .= ' | Class: ' . $class;
        }

        if (!empty($method)) {
            $text .= ' | Method: ' . $method;
        }

        $text = self::clearLog($text);

        if ($isCleanUpBeforeData) {
            self::fileUpdateContent('wilcity-error.log', $text);
        } else {
            self::filePutContents('wilcity-error.log', $text);
        }

        do_action('wilcity/wiloke-listing-tools/write-error-log', $text, $isCleanUpBeforeData);

    }

    public static function logSuccess($text, $class = '', $method = '', $isCleanUpBeforeData = false)
    {
        if (self::focusDisableLog()) {
            return false;
        }

        if (!empty($class)) {
            $text .= ' | Class: ' . $class;
        }

        if (!empty($method)) {
            $text .= ' | Method: ' . $method;
        }

        $text = self::clearLog($text);
        if (!$isCleanUpBeforeData) {
            self::fileUpdateContent('wilcity-success.log', $text);
        } else {
            self::filePutContents('wilcity-success.log', $text);
        }

        do_action('wilcity/wiloke-listing-tools/write-success-log', $text, $isCleanUpBeforeData);
    }

    public static function logAddListing($text, $isFirstStep = false, $class = '', $method = '')
    {
        if (self::focusDisableLog()) {
            return false;
        }

        if (!General::isEnableDebug()) {
            return false;
        }

        if ($isFirstStep) {
            self::filePutContents('addlisting.log', '');
        }

        if (!empty($class)) {
            $text .= ' | Class: ' . $class;
        }

        if (!empty($method)) {
            $text .= ' | Method: ' . $method;
        }

        self::fileUpdateContent('addlisting.log', self::clearLog($text), '', false);
    }

    public static function fileGetContents($fileName, $isCreatedIfNotExists = true, $subFolder = '')
    {
        $fileDir = self::buildFileDir($fileName, $subFolder);

        if (!file_exists($fileDir)) {
            if ($isCreatedIfNotExists) {
                self::createFile($fileName);

                return false;
            }
        }

        if (!function_exists('WP_Filesystem')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        WP_Filesystem();
        global $wp_filesystem;

        return $wp_filesystem->get_contents($fileDir);
    }
}
