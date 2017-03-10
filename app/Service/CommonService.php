<?php
namespace App\Services;


use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;

class CommonService
{

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
        return md5(md5($pass) . substr($pass, 0, 2) . 'exam2017');
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
}
