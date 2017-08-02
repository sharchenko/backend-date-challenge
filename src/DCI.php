<?php

namespace Vice\Challenge;

/**
 * Class DCI
 * @package Vice\Challenge
 */
class DCI implements DifferenceCalculator
{
    /**
     * @param string $start
     * @param string $end
     * @return null|DDI
     * @throws \ArgumentCountError
     */
    public static function diff($start, $end)
    {
        $in = array();
        $result = null;

        foreach (func_get_args() as $arg) {
            preg_match('/^([1-9]\d{3})-(\d{2})-(\d{2})$/', $arg, $matches, PREG_OFFSET_CAPTURE, 0);
            if (count($matches) == 4) {
                $in[] = array(
                    "year" => (int) $matches[1][0],
                    "month" => (int) $matches[2][0],
                    "day" => (int) $matches[3][0],
                );
            } else {
                throw new \InvalidArgumentException("Argument is not a date");
            }
        }

        return new DDI($in[0], $in[1]);
    }
}
