<?php
/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

/**
 * Builds raw insert/update parameters
 *
 * @param  $list
 * @return string
 */
function build_sql_parameters($list)
{
    return '"'.join('", "', $list).'"';
}
