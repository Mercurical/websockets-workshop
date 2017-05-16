<?php

namespace AppBundle\Service\Text;

class Colorify
{
    /**
     * @param string $nickname
     * @param string $ip
     * @return array
     */
    public static function getColorByNicknameAndIP($nickname, $ip)
    {
        $r = (strlen($nickname) % 16) * 16 - 1;

        $sum = 0;
        foreach ((array)$nickname as $letter) {
            $sum += chr($letter);
        }

        $g = $sum % 255;

        $ipParts = explode('.', $ip);

        $b = array_sum($ipParts) % 255;

        $values = ['r' => $r, 'g' => $g, 'b' => $b];

        if ($r + $g + $b > 600) {
            $values = array_map(function($val) {
                return $val - 40;
            }, $values);
        }

        return $values;
    }
}