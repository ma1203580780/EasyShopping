<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2017/2/10
 * Time: 下午5:17
 */
namespace App\Store;

use Illuminate\Support\Facades\DB;

class AdminStore
{
    //表名
    private static $table = 'data_admin';
    private static $limit = 10;

    public static function adminInsert($param){
        if(empty($param)) return false;
        return DB::table(self::$table)->insert($param);
    }

    public static function getFirst($where){
        if(empty($where)) return false;
        return DB::table(self::$table)->where($where)->first();
    }
    /**
     * @param $where
     * @param $param
     * @return bool
     * 更新信息
     */
    public static function adminUpdate($where,$param){
        if(empty($param)) return false;
        return DB::table(self::$table)->where($where)->update($param);
    }

    public static function getAll($page,$where=[]){
        //每页取几条数据
        $limit = self::$limit;
        //偏移量
        $start = ($page-1)*$limit;
        return DB::table(self::$table)->where($where)->skip($start)->take($limit)->get();
    }




}