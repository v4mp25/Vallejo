<?php
require __DIR__ . '/../vendor/autoload.php';
$path = __DIR__ . '/../tests/Feature/AulaVirtualTest.php';
echo "Inspecting: $path\n";
$src = file_get_contents($path);
$tokens = token_get_all($src);
foreach ($tokens as $i => $token) {
    if (is_array($token)) {
        printf("%04d: %s => %s\n", $i, token_name($token[0]), str_replace("\n","\\n", $token[1]));
    } else {
        printf("%04d: CHAR => %s\n", $i, $token);
    }
}
echo "Done\n";
