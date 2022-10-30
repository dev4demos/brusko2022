<?php

declare (strict_types = 1);

namespace App;

require_once __DIR__ . '/Data.php';

class Helper
{
    /**
     * @param string $path
     * @return array
     */
    public static function extractStartEndDate(string $path)
    {
        $pieces = [];

        $path = str_replace(['/', '\\'], ['/', '/'], $path);

        $path = trim(substr($path, strrpos($path, '/')), '/');

        $path = explode('_', $path);

        !$path ?: $pieces['id'] = array_pop($path);

        !$path ?: $path = array_shift($path);

        !$path ?: $path = explode('-', $path);

        !$path ?: $pieces['startDate'] = (int) array_shift($path);

        !$path ?: $pieces['endDate'] = (int) array_shift($path);
        // Format date time
        $pieces['startDateString'] = (string) date('Y-m-d H:i:s', $pieces['startDate']);
        $pieces['endDateString'] = (string) date('Y-m-d H:i:s', $pieces['endDate']);
        //
        return new Data($pieces);
    }

    /**
     * @param int $number
     * @return int
     */
    public static function randomFileNames(int $number, $sessionId = null)
    {
        $items = [];

        foreach (range(1, $number) as $name) {
            $name = static::randomStartDate() . '-' . static::randomEndDate();

            empty($sessionId) ?: $name = $name . '_' . $sessionId;

            $items[$name] = $name;
        }

        return $items;
    }

    /**
     * @param $origin
     * @return int
     */
    public static function randomStartDate(string $target = null, $origin = 1970)
    {
        $origin = new \DateTimeImmutable('1970-01-01');

        $target = new \DateTimeImmutable($target ?: (string) date('Y-m-d H:i:s'));

        return $target->format('U') - $origin->format('U');
    }

    /**
     * @param string $target
     * @return int
     */
    public static function randomEndDate(string $target = null)
    {
        $currentDayTime = (int) (new \DateTimeImmutable($target ?: (string) date('Y-m-d H:i:s')))->format('U');

        $tomorrowDayTime = (int) (new \DateTimeImmutable('tomorrow'))->format('U');

        return mt_rand($tomorrowDayTime, $currentDayTime * 84);
    }

    /**
     * @param $str
     * @return mixed
     */
    public static function utf8Strrev($str)
    {
        $it = \IntlBreakIterator::createCodePointInstance();

        $it->setText($str);

        $ret = '';
        $pos = 0;
        $prev = 0;

        foreach ($it as $pos) {
            $ret = substr($str, $prev, $pos - $prev) . $ret;
            $prev = $pos;
        }

        return $ret;
    }

    /**
     * Check if arg is Palindrome
     *
     * @param mixed $arg
     * @return bool
     */
    public static function isPalindrome($arg)
    {
        return (is_numeric($arg) ? static::isNumberPalindrome($arg) : static::isStringPalindrome($arg)) ? true : false;
    }

    /**
     * Check if arg is Palindrome string
     *
     * @param mixed $arg
     * @return int
     */
    public static function isStringPalindrome(string $arg)
    {
        if (!is_scalar($arg)) {return 0;}

        // return strrev($arg) == $arg ? 1 : 0;
        return static::utf8Strrev($arg) == $arg ? 1 : 0;
    }

    /**
     * Check if arg is Palindrome number
     *
     * @param mixed $arg
     * @return int
     */
    public static function isNumberPalindrome($arg)
    {
        if (!is_scalar($arg)) {return 0;}

        $arg = intval($arg);

        $target = 0;

        $reduce = $arg;

        while (floor($reduce)) {
            $mod = $reduce % 10;

            $target = $target * 10 + $mod;

            $reduce = $reduce / 10;
        }

        return $target === $arg ? 1 : 0;
    }

    /**
     * @param $arg
     */
    public static function redirectTo($uri)
    {
        header("Location: " . $uri);

        exit;
    }

    /**
     * @param $arg
     */
    public static function trim($arg)
    {
        return trim(...func_get_args());
    }

    /**
     * @param $arg
     */
    public static function upper($arg)
    {
        return strtoupper($arg);
    }

    /**
     * @param $arg
     */
    public static function e($arg)
    {
        return htmlspecialchars(...(array_merge([(string) $arg], array_slice(func_get_args(), 1))));
    }

    public static function dd()
    {
        static::dump(...func_get_args());

        exit(1);
    }

    public static function dump()
    {
        foreach (func_get_args() as $arg) {
            echo '<pre>';
            call_user_func('var_dump', $arg);
            echo '</pre>';
        }
    }

    /**
     * @param $path
     */
    public function fileContents($path)
    {
        return file_get_contents($path);
    }

    /**
     * @param $path
     */
    public static function getFiles($path)
    {
        $items = [];

        if (!is_dir($path)) {return $items;}

        foreach (scandir($path, 1) as $item) {
            $item = rtrim($path, '\\/') . DIRECTORY_SEPARATOR . ltrim($item, '\\/');
            if (!is_file($item)) {
                continue;
            }
            $items[] = realpath($item);
        }

        return $items;
    }

    /**
     * @param $path
     */
    public static function requireFiles($path)
    {
        $files = is_dir($path) ? static::getFiles($path) : (is_file($path) ? [$path] : func_get_args());

        foreach ($files as $file) {
            require_once $file;
        }
    }
}
