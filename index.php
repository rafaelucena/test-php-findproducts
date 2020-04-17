<?php

require __DIR__.'/vendor/autoload.php';

use Recruitment\Run;

$run = new Run();
$run->prepare($argv);
$run->execute();