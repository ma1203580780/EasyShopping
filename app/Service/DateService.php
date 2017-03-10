<?php
namespace App\Service;

class DateService
{
    //当前时间戳
    private static $timestamp;
    
    public function __construct()
    {
        $this->timestamp = time();
    }

    public static function week()
    {
        //本周起始时间
        $weekstart  = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y")));
        //本周结束时间
        $weekend    = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y")));
        return ['start'=>$weekstart,'end'=>$weekend];
    }

    public static function month()
    {
        //本月开始时间
        $monthstart = date('Y-m-01', strtotime(date('Y', self::timestamp) . '-' . (date('m', self::timestamp)) . '-01'));
        //本月结束时间
        $monthend   = date("Y-m-d", strtotime("$monthstart +1 month -1 day"));
    }


    public static function season()
    {
        //本季开始时间
        $season = ceil((date('n')) / 3);//当月是第几季度
        $seasonStart = date('Y-m-d H:i:s', mktime(0, 0, 0, $season * 3 - 3 + 1, 1, date('Y')));
        //本季结束时间
        $seasonEnd = date('Y-m-d H:i:s', mktime(23, 59, 59, $season * 3, date('t', mktime(0, 0, 0, $season * 3, 1, date("Y"))), date('Y')));

    }
    public static function year()
    {
        //年度开始时间
        $yearStart = date('Y-01-01', strtotime(date('Y', self::timestamp) . '-01-01'));
        //年度结束时间
        $yearEnd = date('Y-12-31', strtotime(date('Y', self::timestamp) . '-12-31'));
    }
    /**
     *
     * 计算年龄
     * @param $time 出生日对应的时间戳
     * @return bool|int|string
     */
    public static function getAge($time)
    {
        $year_diff = date('Y') - date('Y', $time);
        $mon_diff = date('m') - date('m', $time);
        if ($year_diff == 0) {
            return 0;
        } else {
            if ($mon_diff >= 0) {
                return $year_diff;
            } else {
                return $year_diff - 1;
            }
        }
    }

   
    /**
     * 获取某一年的某一月有多少天
     *
     * @param  $year
     * @param  $month
     * @return int
     */
    public static function t($year, $month)
    {
        switch ($month) {
            case '01':
            case '03':
            case '05':
            case '07':
            case '08':
            case '10':
            case '12':
                return 31;
                break;
            case '04':
            case '06':
            case '09':
            case '11':
                return 30;
                break;
            default:
                if ($year % 4 == 0 && $year % 100 != 0 || $year % 400 == 0) {
                    return 29;
                } else {
                    return 28;
                }
                break;
        }
    }

    /**
     * 获取周
     *
     * @param           数字周
     * @return          英文周
     */
    public static function weekToEn($week)
    {
        switch ($week) {
            case 1:
                return 'Monday';
                break;
            case 2:
                return 'Tuesday';
                break;
            case 3:
                return 'Wednesday';
                break;
            case 4:
                return 'Thursday';
                break;
            case 5:
                return 'Friday';
                break;
            case 6:
                return 'Saturday';
                break;
            case 0:
                return 'Sunday';
                break;
        }
    }

    /**
     *  获取本月第一天和最后一天
     * @param $date
     * @return array
     */
    public static function getMonth($date)
    {
        $firstday = date("Y-m-01", strtotime($date));
        $lastday = date("Y-m-d", strtotime("$firstday +1 month -1 day"));
        return array($firstday, $lastday);
    }


    /**
     *  获取上个月第一天和最后一天
     * @param $date
     * @return array
     */
    public static function getlastMonthDays($date)
    {
        $timestamp = strtotime($date);
        $firstday = date('Y-m-01', strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp) - 1) . '-01'));
        $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        return array($firstday, $lastday);
    }


    /**
     *  获取下个月第一天和最后一天
     * @param $date
     * @return array
     */
    public static function getNextMonthDays($date)
    {
        $timestamp = strtotime($date);
        $arr = getdate(strtotime($date));
        if ($arr['mon'] == 12) {
            $year = $arr['year'] + 1;
            $month = $arr['mon'] - 11;
            $firstday = $year . '-0' . $month . '-01';
            $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        } else {
            $firstday = date('Y-m-01', strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp) + 1) . '-01'));
            $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        }
        return array($firstday, $lastday);
    }

   
}

   
