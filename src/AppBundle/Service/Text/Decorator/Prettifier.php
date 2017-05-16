<?php

namespace AppBundle\Service\Text\Decorator;

class Prettifier
{
    /**
     * @param string $sender
     * @param string $msg
     * @param array $rgb
     * @return string
     */
    public static function getPrettyMessage($sender, $msg, $rgb)
    {
        if(!$msg) {
            return '';
        }

        return "<p><span style=\"color: rgb({$rgb['r']}, {$rgb['g']}, {$rgb['b']});\">{$sender}</span>: {$msg}</p>";
    }

    /**
     * @param array $list
     * @return string
     */
    public static function getPrettyClientIPList($list)
    {
        $prettyList = array_map(function($val) {
            return "<p>{$val}</p>";
        }, $list);

        return implode('', $prettyList);
    }
}