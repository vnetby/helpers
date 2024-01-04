<?php

namespace Vnetby\Helpers;


class HelperStr
{

    /**
     * - Склонение числительных
     * @param int $numeber число
     * @param array $titles массив строк из 3-х элементов:
     *   ['Сидит %d котик', 'Сидят %d котика', 'Сидит %d котиков']
     */
    static function declenNum(int $number, array $titles): string
    {
        $cases = [2, 0, 1, 1, 1, 2];
        $format = $titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
        return sprintf($format, $number);
    }


    /**
     * - Обрезает строку до кол-ва символов
     * @param string $str 
     * @param int $softCount сколько слов оставить
     * @param int $hardCount на какое максимальное кол-во слов делать проверку
     * @return string 
     */
    static function cut(string $str, int $softCount, int $hardCount, string $moreStr = ' ...'): string
    {
        $str = trim(strip_tags($str));

        preg_match_all("/([^\s]{3,})/miu", $str, $matches);

        if (count($matches[0]) <= $hardCount) {
            return $str;
        }

        preg_match_all("/([^\s]+)/miu", $str, $matches);

        $newStr = '';
        $countWords = 0;

        foreach ($matches[0] as $word) {
            if ($countWords > $softCount) {
                $newStr .= $moreStr;
                break;
            }
            if (!$newStr) {
                $newStr = $word;
            } else {
                $newStr .= ' ' . $word;
            }
            $countWords++;
        }

        return $newStr;
    }


    /**
     * - Получает название класса из строки с названием класса и пространством имен
     *
     * @param string $fullClassName
     * @return string
     */
    static function getClassName(string $fullClassName): string
    {
        preg_match("/\\\([^\\\]+)$/", $fullClassName, $mathes);
        return $mathes[1] ?? '';
    }
}
