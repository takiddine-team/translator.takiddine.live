<?php



function dd($dd) {
    sv($dd);
    exit;
}
function sv($x) {
    echo '<pre>';
    print_r($x);
    echo '</pre>';
}

function flattenArray($array) {
    $result = [];

    foreach ($array as $value) {
        if (is_array($value)) {
            $result = array_merge($result, flattenArray($value));
        } else {
            $result[] = $value;
        }
    }

    return $result;
}



function convertSecondsToReadable($seconds) {
    $days = floor($seconds / 86400); // 1 day has 86400 seconds
    $seconds -= $days * 86400;

    $hours = floor($seconds / 3600); // 1 hour has 3600 seconds
    $seconds -= $hours * 3600;

    $minutes = floor($seconds / 60);
    $seconds -= $minutes * 60;

    $result = '';
    if ($days > 0) {
        $result .= $days . ' day' . ($days > 1 ? 's' : '') . ', ';
    }
    if ($hours > 0) {
        $result .= $hours . ' hour' . ($hours > 1 ? 's' : '') . ', ';
    }
    if ($minutes > 0) {
        $result .= $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ', ';
    }
    if ($seconds > 0) {
        $result .= $seconds . ' second' . ($seconds > 1 ? 's' : '');
    }

    return rtrim($result, ', ');
}