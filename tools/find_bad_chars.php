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
        // allow TAB(9), LF(10), CR(13), FF(12)
        if ($ord === 9 || $ord === 10 || $ord === 13 || $ord === 12) continue;
        if ($ord >= 32) continue; // printable and extended
        // otherwise record
        $badPositions[] = ['pos' => $i, 'byte' => $ord];
    }
    // check for BOM anywhere
    $bomPositions = [];
    $offset = 0;
    while (($idx = strpos($data, "\xEF\xBB\xBF", $offset)) !== false) {
        $bomPositions[] = $idx;
        $offset = $idx + 3;
    }
    if (!empty($badPositions) || !empty($bomPositions)) {
        $badFiles[$path] = ['bad' => $badPositions, 'bom' => $bomPositions];
    }
}
if (empty($badFiles)) {
    echo "No bad bytes or BOMs found in PHP files.\n";
    exit(0);
}
foreach ($badFiles as $path => $info) {
    echo "File: $path\n";
    if (!empty($info['bom'])) {
        foreach ($info['bom'] as $p) echo "  BOM at $p\n";
    }
    if (!empty($info['bad'])) {
        foreach ($info['bad'] as $b) {
            $p = $b['pos']; $byte = $b['byte'];
            $start = max(0, $p - 20);
            $end = min(strlen(file_get_contents($path)), $p + 20);
            $context = substr(file_get_contents($path), $start, $end - $start);
            $hex = implode(' ', array_map(function($c){return sprintf('%02X', ord($c));}, str_split($context)));
            echo "  BAD byte {$byte} at pos {$p}\n";
            echo "    Context (hex): {$hex}\n";
            echo "    Context (str): ".preg_replace('/[\x00-\x1F\x7F]/','.', $context)."\n";
        }
    }
}
exit(1);
