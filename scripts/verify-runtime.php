<?php

// Keep this independent of Composer so it can run before dependencies are installed.
function dicewareRuntimeIsSupported($version)
{
    if (!preg_match('/^(8\.[2-5])\.([0-9]+)(?:[-+].*)?$/D', $version, $matches)) {
        return false;
    }

    $floors = ['8.2' => '8.2.35', '8.3' => '8.3.36', '8.4' => '8.4.27', '8.5' => '8.5.12'];

    return version_compare($version, $floors[$matches[1]], '>=');
}

if (!dicewareRuntimeIsSupported(PHP_VERSION)) {
    throw new RuntimeException('Unsafe or unreviewed PHP runtime: '.PHP_VERSION.
        '. Require PHP 8.2.35+, 8.3.36+, 8.4.27+, or 8.5.12+ within those branches.');
}
