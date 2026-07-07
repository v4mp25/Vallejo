<?php
file_put_contents(__DIR__ . '/included_files_before.txt', "Auto-prepend start\n");
register_shutdown_function(function(){
    $f = __DIR__ . '/included_files_after.txt';
    $files = get_included_files();
    file_put_contents($f, implode("\n", $files));
});
