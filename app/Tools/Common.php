<?php
namespace App\Tools;


use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;

class Common
{
    private static $apiUrl = CURLPAI;
    private static $cryptToken = CRYPTTOKEN;
    private static $curlGuid = CURLGUID;

    /**
     * CURLf方法
     * @param $url
     * @param bool $param
     * @param int $ispost
     * @param int $https
     * @return bool|mixed
     */
    public static function curl($url, $param = false, $ispost = 1, $https = 0)
    {
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        }

        $url = self::$apiUrl . $url;
        $cryptToken = self::$cryptToken;
        $time = time();
        $guid = self::$curlGuid;
//        $group_guid = GROUP_GUID;


        $param = json_encode($param);
        $signature = md5(md5($time . $guid . $param . $cryptToken), '39ad5014fc77197034142f8a45c4b966');
        $data = array(
            'signature' => $signature,
            'param' => $param,
            'time' => $time,
            'guid' => $guid,
        );
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($data) {
                if (is_array($data)) {
                    $data = http_build_query($data);
                }
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $data);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        $response = curl_exec($ch);

        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }


    public static function doCurl($url, $params = false, $ispost = 0, $https = 0)
    {
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        }
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                if (is_array($params)) {
                    $params = http_build_query($params);
                }
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        $response = curl_exec($ch);

        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }

    /**
     * 邮件发送
     * @param $name  收件人姓名
     * @param $to    收件人邮箱
     * @param $title 邮件标题
     * @param $url   链接地址
     * @param string $blade 邮箱模板
     * @return bool
     */
    public static function sendEmail($name, $to, $title, $url, $blade = 'register')
    {
        // 邮件发送
        $flag = Mail::send('email.' . $blade, ['name' => $name, 'url' => $url], function ($message) use ($to, $title) {
            // 发送
            $message->to($to)->subject('【Microlanguage】' . $title);
        });
        // 判断发送结果
        if ($flag) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $name
     * @param $to
     * @param $title
     * @param $url
     * @param string $blade
     * @return bool
     */
    public static function sendEmail2($name, $to, $title, $data, $blade = 'createClassroom')
    {
        // 邮件发送
        $flag = Mail::send('email.' . $blade, ['name' => $name, 'data' => $data, 'title' => $title], function ($message) use ($to, $title) {
            // 发送
            $message->to($to)->subject('【紧急邮件】' . $title);
        });
        // 判断发送结果
        if ($flag) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 数组转换对象
     *
     * @param $e 数组
     * @return object|void
     */
    public static function arrayToObject($e)
    {

        if (gettype($e) != 'array') return;
        foreach ($e as $k => $v) {
            if (gettype($v) == 'array' || getType($v) == 'object')
                $e[$k] = (object)self::arrayToObject($v);
        }
        return (object)$e;
    }

    /**
     * 对象转换数组
     *
     * @param $e StdClass对象实例
     * @return array|void
     */
    public static function objectToArray($e)
    {
        $e = (array)$e;
        foreach ($e as $k => $v) {
            if (gettype($v) == 'resource') return;
            if (gettype($v) == 'object' || gettype($v) == 'array')
                $e[$k] = (array)self::objectToArray($v);
        }
        return $e;
    }

    /**
     * 密码加密函数
     * @param $pass
     * @return string
     */
    public static function passMcrypt($pass)
    {
        return md5(md5($pass) . substr($pass, 0, 2) . 'chaoniu2017');
    }


    /**
     * 知道一个时间戳，获取月初时间戳
     * @param $times
     * @return string
     */

    public static function ltimes($times)
    {
        return mktime(0, 0, 0, date('m', $times), 1, date('Y', $times));
    }


    /**
     * 知道一个时间戳，获取月底时间戳
     * @param $pass
     * @return string
     */

    public static function rtimes($times)
    {
        return mktime(23, 59, 59, date('m', $times), date('t', $times), date('Y', $times));
    }

    /**
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
     * 获取唯一识别id
     * @param
     * @return uuid|string
     */
    public static function getUuid()
    {
        //生成uuid
        $temp = Uuid::uuid1();
        $uuid = $temp->getHex();#uuid
        return $uuid;
    }

    /**
     * 递归删除目录
     *
     * @param  string $path 要删除的目录
     * @return void
     */
    public static function delDir($path)
    {
        if ($path != '/') {
            //判断文件是否存在
            if (file_exists($path)) {
                //打开目录句柄
                $handler = opendir($path);
                //遍历删除
                while (($res = readdir($handler)) !== false) {
                    //过滤掉  .   ..
                    if ($res == '.' || $res == '..') {
                        continue;
                    }
                    //创建有效路径
                    $validPath = rtrim($path, '/') . '/' . $res;
                    //是文件则直接删除
                    if (is_file($validPath)) {
                        unlink($validPath);
                    }
                    //是目录递归删除
                    if (is_dir($validPath)) {
                        self::delDir($validPath);
                    }
                }
                closedir($handler);
                rmdir($path);
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
    public static function week($week)
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
        $arr = getdate($timestamp);
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

    /**
     * @param $server_no
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function wResponse($data = array(), $server_no = 'SN000')
    {
        return response()->json([
            'ServerTime' => time(),
            'ServerNo' => $server_no,
            'ResultData' => $data
        ]);
    }

    /**
     * 时间戳转换时间点算法
     *
     * @param $toDay
     * @param $start
     * @param $minutes
     * @return array
     * @author Luoyan
     */
    public static function getPointInTime($toDay, $start, $minutes)
    {
        // 获取时间点配置数组
        $times = config('time-point.POINT_TIME');
        // 时间点
        $point = [];
        foreach ($times as $k => $v) {
            // 获取时间点对应的时间戳
            $configTimestam = $toDay + $v;
            // 获取上课时间戳
            if (($k == 28) && ($configTimestam <= $start)) return [$k];
            // 判断当前上课时间对应的时间点
            if (($configTimestam <= $start) && $start < ($toDay + $times[$k + 1])) {
                $point[] = $k;
                // 上课分钟
                $currentMin = date('i', $start);
                if ($currentMin > 30) {
                    if (($currentMin + $minutes) > 60) $point[] = $k + 1;
                    if (end($point) == 28) return $point;
                    if (($currentMin + $minutes - 60) > 30) $point[] = $k + 2;
                } else {
                    if (($currentMin + $minutes) > 30) $point[] = $k + 1;
                }
            }
        }
        return $point;
    }
}
