<?php
$file = $argv[1] ?? null;
if (!$file) { echo "usage: php inspect_single_file_tokens.php path\n"; exit(2); }
$s = file_get_contents($file);
$t = token_get_all($s);
foreach ($t as $tok) {
    if (is_array($tok)) {
        printf("%s => %s\n", token_name($tok[0]), str_replace(["\r","\n"], ['\\r','\\n'], substr($tok[1],0,200)));
    } else {
        printf("CHAR => %s\n", $tok);
    }
}
