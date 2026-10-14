<?php

/** Resolve physical presentation tables for legacy demo seeders (views cannot TRUNCATE). */
function website_presentation_table(string $table): string
{
    $db = db_connect();
    $legacy = 'legacy_' . $table;
    return $db->tableExists($legacy) ? $legacy : $table;
}
