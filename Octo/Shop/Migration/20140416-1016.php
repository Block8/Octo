<?php
$queries = [];

$queries[] = "ALTER TABLE `item_variant`
ADD `price_adjustment` DECIMAL(10,2)  NOT NULL  DEFAULT 0.00  AFTER `variant_option_id`;";
