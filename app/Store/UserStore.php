<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2017/2/10
 * Time: 下午5:17
 */
namespace App\Store;

use Illuminate\Support\Facades\DB;

class UserStore
{
    //表名
    private static $table = 'users';


    public static function userInsert($param){
        if(empty($param)) return false;
        return DB::table(self::$table)->insert($param);
    }

    public static function getFirst($where){
        if(empty($where)) return false;
        return DB::table(self::$table)->where($where)->first();
    }

    public static function getInfo($where){
        if(empty($where)) return false;
        return DB::table(self::$table)->where($where)->first();
    }

    /**
     * @param $where
     * @param $param
     * @return bool
     * 更新信息
     */
    public static function userUpdate($where,$param){
        if(empty($param)) return false;
        return DB::table(self::$table)->where($where)->update($param);
    }




}