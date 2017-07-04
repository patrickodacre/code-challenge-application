<?php

    /**
     * Use when you want to create an associative
     * array keyed by a $key.
     * Unlike key_by, each key may have multiple arrays
     * as its value.
     * 
     * @author Patrick O'Dacre <patrick@patrickwho.me>
     * 
     * @param array $array The subject array
     * @param string $key The array key to group data under
     * @return array Returns a new associative array
     */
    function group_by($array, $key) {
        return array_reduce($array, function ($carry, $item) use ($key) {
            $carry[$item[$key]][] = $item;
            return $carry;
        }, []);
    }

    /**
     * Use when you want to create an associative
     * array keyed by a $unique_key.
     * Unlike group_by, each key will have a single array
     * as its value.
     * 
     * @author Patrick O'Dacre <patrick@patrickwho.me>
     * 
     * @param array $array The subject array
     * @param string $unique_key The array key to group data under
     * @return array Returns a new associative array
     */
    function key_by($array, $unique_key) {
        return array_reduce($array, function ($carry, $item) use ($unique_key) {
            $carry[$item[$unique_key]] = $item;
            return $carry;
        }, []);
    }