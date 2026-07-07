<?php
$path = realpath(__DIR__ . '/../vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Shared/StringHelper.php');
if (!$path) { echo "file not found\n"; exit(2); }
$pos = 22857;
$len = 100;
$data = file_get_contents($path);
$start = max(0, $pos - 40);
$chunk = substr($data, $start, $len+80);
$hex = '';
$chars = '';
for ($i=0; $i<strlen($chunk); $i++){
    $b = ord($chunk[$i]);
    $hex .= sprintf('%02X ', $b);
    $chars .= ($b >= 32 && $b <= 126) ? $chunk[$i] : sprintf('\x%02X', $b);
}
printf("Context start pos: %d\n", $start);
echo "HEX: \n".wordwrap(trim($hex), 48, "\n")."\n";
echo "STR: \n".$chars."\n";
echo "---\n";
for ($i=0;$i<strlen($chunk);$i++){
    $p = $start + $i;
    $b = ord($chunk[$i]);
    printf("%6d: %02X %s\n", $p, $b, ($p == $pos ? '<-- pos' : ''));
}
