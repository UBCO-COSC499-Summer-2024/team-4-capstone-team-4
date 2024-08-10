<?php

if (!function_exists('goToDash')) {
    function goToDash() {
        $url = route('svcroles');
        header("Location: $url");
        exit();
    }
}
