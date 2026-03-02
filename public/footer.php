<?php
// Fetch distro data using the helper function (works on both Linux and Windows)
if (!function_exists('fetchApiData')) {
    require_once __DIR__ . '/../config/api_helper.php';
}

$distro = null;
$api_file_path = __DIR__ . '/../api/distro/list.php';

if (file_exists($api_file_path)) {
    $data = @fetchApiData($api_file_path);
    $distro = isset($data['data'][0]) ? $data['data'][0] : null;
}
?>
