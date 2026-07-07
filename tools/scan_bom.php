<?php
$root = __DIR__ . '/../';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
$bad = [];
foreach ($it as $file) {
    if (!$file->isFile()) continue;
    $path = $file->getPathname();
    if (!preg_match('/\.php$/i', $path)) continue;
    $content = file_get_contents($path);
    $bytes = substr($content, 0, 3);
    $bom = ($bytes === "\xEF\xBB\xBF");
    $hasNull = strpos($content, "\0") !== false;
    // detect unexpected high-bytes near start
    $prefix = substr($content, 0, 200);
    $nonprint = false;
    for ($i=0;$i<strlen($prefix);$i++){
        $ord = ord($prefix[$i]);
        if ($ord < 9) { $nonprint = true; break; }
    }
    if ($bom || $hasNull || $nonprint) {
        $bad[] = [
            'file' => $path,
            'bom' => $bom,
            'null' => $hasNull,
            'nonprint' => $nonprint,
        ];
    }
}
if (empty($bad)) {
    echo "No suspicious PHP files found.\n";
    exit(0);
}
foreach ($bad as $b) {
    echo ($b['bom']?"BOM":"   ") . " " . ($b['null']?"NULL":"   ") . " " . ($b['nonprint']?"NP":"  ") . " " . $b['file'] . "\n";
}
exit(1);
