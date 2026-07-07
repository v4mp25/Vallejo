<?php
$root = __DIR__ . '/../';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
$badFiles = [];
foreach ($it as $file) {
    if (!$file->isFile()) continue;
    $path = $file->getPathname();
    if (!preg_match('/\.php$/i', $path)) continue;
    $data = file_get_contents($path);
    $len = strlen($data);
    $badPositions = [];
    for ($i = 0; $i < $len; $i++) {
        $ord = ord($data[$i]);
        if (($ord >= 0x80 && $ord <= 0x9F)) {
            $badPositions[] = ['pos' => $i, 'byte' => $ord];
            if (count($badPositions) > 10) break;
        }
    }
    if (!empty($badPositions)) {
        $badFiles[$path] = $badPositions;
    }
}
if (empty($badFiles)) { echo "No high-control bytes found.\n"; exit(0);} 
foreach ($badFiles as $path => $info) {
    echo "File: $path\n";
    foreach ($info as $b) echo "  byte {$b['byte']} at {$b['pos']}\n";
}
exit(1);
