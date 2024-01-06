<?php

namespace Vnetby\Helpers;

class HelperDate
{

    protected static $timeZone = null;


    /**
     * - Форматирует дату
     *
     * @param string $format
     * @param string $date
     * @return string
     */
    static function format(string $format, string $date): string
    {
        return self::utcFunc(function () use ($format, $date) {
            if (preg_match("/[\d]{4}[\d]{2}[\d]{2}/", $date)) {
                $date = preg_replace("/([\d]{4})([\d]{2})([\d]{2})/", "$1-$2-$3", $date);
            }
            $time = is_numeric($date) ? $date : strtotime($date);
            $newDate = date($format, $time);
            return $newDate;
        });
    }


    /**
     * - Получает сокращенное название месяца
     *
     * @param integer $monthNum
     * @return string
     */
    static function getRuMoth(int $monthNum): string
    {
        $monthes = ['Янв', 'Февр', 'Марта', 'Апр', 'Мая', 'Июня', 'Июля', 'Авг', 'Сент', 'Окт', 'Ноя', 'Дек'];
        return $monthes[$monthNum - 1];
    }


    /**
     * - Формирует короткую строку даты на русском
     *
     * @param string $date
     * @return string
     */
    static function ruShortDate(string $date): string
    {
        return self::utcFunc(function () use ($date) {
            $time = strtotime($date);
            $day = date('d', $time);
            $year = date('Y', $time);
            $thisYear = date('Y', time());
            $month = self::getRuMoth(date('n', $time));
            if ($thisYear !== $year) {
                return "{$day} {$month} {$year}";
            }
            return "{$day} {$month}";
        });
    }


    /**
     * - Формирует короткую русскую строку диапазона дат
     *
     * @param string $start
     * @param string $end
     * @return string
     */
    static function ruShortDateRange(string $start, string $end): string
    {
        return self::utcFunc(function () use ($start, $end) {
            $endStr = self::ruShortDate($end);
            if (date('m', strtotime($start)) !== date('m', strtotime($end))) {
                $startDay = self::ruShortDate($start);
            } else {
                $startDay = date('d', strtotime($start));
            }
            return $startDay . ' - ' . $endStr;
        });
    }


    /**
     * - Формирует короткую строку диапазона дат
     *
     * @param string $start
     * @param string $end
     * @return string
     */
    static function shortDateRange(string $start, string $end): string
    {
        return self::utcFunc(function () use ($start, $end) {
            $arrStart = [self::format('Y', $start), self::format('m', $start), self::format('d', $start)];
            $arrEnd = [self::format('Y', $end), self::format('m', $end), self::format('d', $end)];

            if (($arrStart[0] !== $arrEnd[0]) && ($arrStart[1] !== $arrEnd[1]) && ($arrStart[2] !== $arrEnd[2])) {
                return implode('.', array_reverse($arrStart)) . ' - ' . implode('.', array_reverse($arrEnd));
            }

            if (($arrStart[1] !== $arrEnd[1]) && ($arrStart[2] !== $arrEnd[2])) {
                array_shift($arrStart);
                return implode('.', array_reverse($arrStart)) . '-' . implode('.', array_reverse($arrEnd));
            }

            if ($arrStart[2] !== $arrEnd[2]) {
                return $arrStart[2] . '-' . implode('.', array_reverse($arrEnd));
            }

            return implode('.', array_reverse($arrStart));
        });
    }


    /**
     * - Добавляет дни к дате
     * @param string $date 
     * @param int $days 
     * @param string $format возвращаемый формат даты
     * @return string 
     */
    static function addDays(string $date, int $days, string $format = 'Y-m-d'): string
    {
        return self::utcFunc(function () use ($date, $days, $format) {
            return date($format, strtotime($date . ' + ' . $days . ' days'));
        });
    }


    /**
     * - Получает название дня недели на русском
     *
     * @param string $date
     * @return string
     */
    static function getRuDay(string $date): string
    {
        $daysRu = [
            'Воскресенье',
            'Понедельник',
            'Вторник',
            'Среда',
            'Четверг',
            'Пятница',
            'Суббота'
        ];

        return self::utcFunc(function () use ($date, $daysRu) {
            $numDay = date('w', strtotime($date));
            return $daysRu[$numDay];
        });
    }


    /**
     * - Певодит строку даты во время
     *
     * @param string $date
     * @return integer
     */
    static function toTime(string $date): int
    {
        return self::utcFunc(function () use ($date) {
            return strtotime($date);
        });
    }


    /**
     * - Выполнит футнкцию во временной зоне UTC
     * - Вернет результат выполнения перданной функции
     * @param callable $fn 
     * @return mixed 
     */
    protected static function utcFunc(callable $fn)
    {
        self::$timeZone = date_default_timezone_get();
        self::setUtc();
        $val = call_user_func($fn);
        self::restoreTimezone();
        self::$timeZone = null;
        return $val;
    }

    protected static function setUtc()
    {
        date_default_timezone_set('UTC');
    }

    protected static function restoreTimezone()
    {
        date_default_timezone_set(self::$timeZone);
    }
}
