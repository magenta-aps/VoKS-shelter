<?php
/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

function array_map_by_key_value($array, $key, $value_key)
{
    $new = [];
    foreach ($array as $item) {
        $new[$item[$key]] = $item[$value_key];
    }

    return $new;
}

function array_reindex($array, $key)
{
    $new = [];
    foreach ($array as $item) {
        $new[$item[$key]] = $item;
    }

    return $new;
}

function array_unflatten($array)
{
    $result = [];
    foreach ($array as $key => $value) {
        if (strpos($key, '.') !== false) {
            parse_str('result[' . str_replace('.', '][', $key) . "]=" . $value);
        } else {
            $result[$key] = $value;
        }
    }

    return $result;
}

function array_with_id_and_label($array)
{
    $result = [];
    foreach ($array as $key => $value) {
        $result[] = ['id' => $key, 'label' => $value];
    }

    return $result;
}

function json_encode_values($data)
{
    $json = [];
    foreach ($data as $key => $array) {
        $json[$key] = json_encode($array);
    }

    return $json;
}

/**
 * Merge array properties that aren't blank
 *
 * @return array
 */
function array_merge_filled()
{
    $overrides = func_get_args();
    $defaults = array_shift($overrides);

    foreach ($overrides as $override) {
        foreach ($override as $key => $item) {
            if (isset($defaults[$key])) {
                if (!empty($item)) {
                    $defaults[$key] = $item;
                }
            } else {
                $defaults[$key] = $item;
            }
        }
    }

    return $defaults;
}

function array_map_by_key($original, $key)
{
    $array = [];

    foreach ($original as $item) {
        $array[$item[$key]] = $item;
    }

    return $array;
}

function is_numeric_array($array)
{
    return array_keys($array) !== range(0, count($array) - 1);
}

function array_fill_by_keys(array $original, array $replacement, array $keys = [])
{
    if (!empty($replacement) && !empty($keys)) {
        $count = count($keys);

        for ($i = 0; $i < $count; $i++) {
            if (isset($replacement[$keys[$i]])) {
                $original[$keys[$i]] = $replacement[$keys[$i]];
            }
        }
    }

    return $original;
}

function array_map_keys(array $original, array $keys)
{
    $array = [];
    foreach ($keys as $new => $origin) {
        if (isset($original[$origin])) {
            $array[$new] = $original[$origin];
        }
    }

    return $array;
}

function array_convert_to_numeric($data)
{
    if (!isset($data[0])) {
        return [$data];
    }

    return $data;
}
