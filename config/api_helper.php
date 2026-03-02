<?php
/**
 * Helper function to fetch API data using HTTP request
 * Works on Windows XAMPP, Linux, and all shared hosting environments
 */

/**
 * Fetch API data by making an HTTP request to the API endpoint
 *
 * @param string $api_file_path Absolute path to the API file
 * @return array|null Decoded JSON data or null on failure
 */
function fetchApiData($api_file_path) {
    if (!file_exists($api_file_path)) {
        return null;
    }

    // Must be running in web server context
    if (!isset($_SERVER['HTTP_HOST'])) {
        error_log("fetchApiData: Not running in web server context");
        return null;
    }

    $url = getApiUrl($api_file_path);
    if ($url === null) {
        return null;
    }

    // Try cURL first (most reliable)
    $data = fetchViaCurl($url);
    if ($data !== null) {
        return $data;
    }

    // Fallback to file_get_contents (if allow_url_fopen is enabled)
    return fetchViaFileGetContents($url);
}

/**
 * Fetch via cURL
 */
function fetchViaCurl($url) {
    if (!function_exists('curl_init')) {
        return null;
    }

    $ch = curl_init($url);
    if ($ch === false) {
        return null;
    }

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json'
        ]
    ]);

    $output = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($httpCode === 200 && !empty($output) && empty($error)) {
        $data = json_decode($output, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            // Accept both {'success': true} and {'status': 'success'} formats
            if (isset($data['success']) && $data['success'] === true) {
                return $data;
            }
            if (isset($data['status']) && $data['status'] === 'success') {
                $data['success'] = true;
                return $data;
            }
        }
    }

    return null;
}

/**
 * Fetch via file_get_contents
 */
function fetchViaFileGetContents($url) {
    if (!ini_get('allow_url_fopen')) {
        return null;
    }

    $context = stream_context_create([
        'http' => [
            'timeout' => 30,
            'ignore_errors' => true,
            'header' => "Accept: application/json\r\n"
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ]
    ]);

    $output = @file_get_contents($url, false, $context);

    if (!empty($output)) {
        $data = json_decode($output, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            // Accept both {'success': true} and {'status': 'success'} formats
            if (isset($data['success']) && $data['success'] === true) {
                return $data;
            }
            if (isset($data['status']) && $data['status'] === 'success') {
                $data['success'] = true;
                return $data;
            }
        }
    }

    return null;
}

/**
 * Convert file path to URL
 * API is in ROOT (/api/), public files in /public/
 */
function getApiUrl($api_file_path) {
    if (!isset($_SERVER['HTTP_HOST'])) {
        return null;
    }

    // Determine protocol
    $protocol = 'http';
    if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) {
        $protocol = 'https';
    }

    $host = $_SERVER['HTTP_HOST'];
    
    // Get base path from SCRIPT_NAME
    // When accessing /public/index.php, SCRIPT_NAME = /distro/public/index.php
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
    $basePath = dirname($scriptName);
    
    // Normalize API path
    $apiPath = str_replace('\\', '/', $api_file_path);
    
    // Find /api/ in path and extract relative path
    $apiPos = strpos($apiPath, '/api/');
    if ($apiPos !== false) {
        $relativePath = substr($apiPath, $apiPos + 1);
    } else {
        $apiDir = basename(dirname($apiPath));
        $apiFile = basename($apiPath);
        $relativePath = $apiDir . '/' . $apiFile;
    }
    
    // API is in ROOT, so URL is /distro/api/...
    // basePath is /distro/public, we need /distro/api
    // So we go up one level from basePath
    $parentPath = dirname($basePath);
    if ($parentPath === '/' || $parentPath === '\\') {
        $parentPath = '';
    }
    
    $urlPath = $parentPath . '/' . $relativePath;
    $urlPath = '/' . ltrim(preg_replace('#/+#', '/', $urlPath), '/');

    return $protocol . '://' . $host . $urlPath;
}
?>
