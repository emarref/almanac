<?php

namespace Emarref\Almanac\Util;

class DataFormatUtil
{
    /**
     * Converts a key value array to a more CSVish table array with column headings instead of row headings.
     *
     * @param array $data
     * @return array
     */
    public static function tableize(array $data)
    {
        $table = array();

        foreach ($data as $i => $row) {
            if (0 === $i) {
                $table[] = array_keys($row);
            }

            $table[] = array_values($row);
        }

        return $table;
    }
}