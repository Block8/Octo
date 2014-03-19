<?php


$module = $argv[1];
$base = 'Octo/';

@mkdir($base . $module . '/Admin/Controller', 0755, true);
@mkdir($base . $module . '/Admin/Template', 0755, true);
@mkdir($base . $module . '/Block', 0755, true);
@mkdir($base . $module . '/Command', 0755, true);
@mkdir($base . $module . '/Controller', 0755, true);
@mkdir($base . $module . '/Event', 0755, true);
@mkdir($base . $module . '/Model/Base', 0755, true);
@mkdir($base . $module . '/Store/Base', 0755, true);
@mkdir($base . $module . '/Template/Block', 0755, true);
