<?php

namespace Chicoco\Core;

class Sanitizer
{
    public static function clear($var, $type)
    {
        return self::sanitizeVar($var, $type);
    }

    private static function sanitizeVar($var, $type = 'string')
    {
        $filters = array(
            'string' => FILTER_SANITIZE_STRING,
            'email' => FILTER_SANITIZE_EMAIL,
            'float' => FILTER_SANITIZE_NUMBER_FLOAT,
            'int' => FILTER_SANITIZE_NUMBER_INT,
            'url' => FILTER_SANITIZE_URL,
            'bool' => FILTER_CALLBACK,
            'raw' => FILTER_UNSAFE_RAW,
            'stringHtml' => FILTER_CALLBACK,
            'datetime' => FILTER_CALLBACK,
            'ipaddr' => FILTER_CALLBACK,
            'array' => FILTER_UNSAFE_RAW,
            'arrayInt' => FILTER_SANITIZE_NUMBER_INT,
            'arrayString' => FILTER_SANITIZE_STRING,
            'arrayEmail' => FILTER_SANITIZE_EMAIL,
            'arrayUrl' => FILTER_SANITIZE_URL,
            'arrayFloat' => FILTER_SANITIZE_NUMBER_FLOAT
        );

        $callbacks = [
            'stringHtml' => ['options' => ['self', 'filterHtml']],
            'datetime' => ['options' => ['self', 'filterDate']],
            'ipaddr' => ['options' => ['self', 'filterIpaddr']],
            'bool' => ['options' => ['self', 'filterBool']],
        ];

        $flags = [
            'float' => FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND,
            'arrayFloat' => FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND
        ];

        if (isset($filters[$type])) {
            switch ($type) {
                case 'stringHtml':
                case 'datetime':
                case 'ipaddr':
                case 'bool':
                    return filter_var($var, $filters[$type], $callbacks[$type]);
                    break;
                case 'array':
                    if (is_array($var)) {
                        return $var;
                    }
                    return null;
                    break;
                case 'arrayInt':
                case 'arrayString':
                case 'arrayEmail':
                case 'arrayUrl':
                case 'arrayFloat':
                    if (!isset($flags[$type])) {
                        $flags[$type] = null;
                    }
                    return self::filterArray($var, $filters[$type], $flags[$type]);
                    break;
                case 'raw':
                    return $var;
                    break;
                default:
                    if (!isset($flags[$type])) {
                        $flags[$type] = null;
                    }
                    return filter_var($var, $filters[$type], $flags[$type]);
            }
        } else {
            return $var;
        }
    }

    private static function filterHtml(string $s)
    {
        return strip_tags($s, '<p><b><ul><ol><li>');
    }

    private static function filterDate(string $d)
    {
        $validChars = '0123456789-:/. ';
        return self::filter($d, $validChars);
    }

    private static function filterIpaddr(string $s)
    {
        $validChars = '0123456789.';
        return self::filter($s, $validChars);
    }

    private static function filterBool($v)
    {
        if (strtolower($v) == 'false') {
            return false;
        }

        return boolval($v);
    }

    private static function filter($d, $validChars)
    {
        $sanitized = '';

        for ($i = 0; $i < strlen($d); $i++) {
            $c = $d[$i];
            if (strpos($validChars, $c) === false) {
                continue;
            }
            $sanitized .= $c;
        }
        return $sanitized;
    }

    private static function filterArray($data, $filter, $flags = null)
    {
        if (!is_array($data)) {
            return null;
        }

        if ($filter == null) {
            return $data;
        }

        foreach ($data as $k => $v) {
            $data[$k] = filter_var($v, $filter, $flags);
        }
        return $data;
    }
}
