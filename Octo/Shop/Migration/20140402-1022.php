<?php
$queries = [];

$queries[] = "ALTER TABLE `item_variant` ADD UNIQUE INDEX (`item_id`, `variant_id`, `variant_option_id`);";
