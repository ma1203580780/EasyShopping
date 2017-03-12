<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2017/2/10
 * Time: 下午5:17
 */
namespace App\Store;

use Illuminate\Support\Facades\DB;

class PlanStore
{
    //表名
    private static $table = 'data_plan';
    private static $limit = 10;

    public static function planInsert($param){
        if(empty($param)) return false;
        return DB::table(self::$table)->insert($param);
    }

    public static function getFirst($where){
        if(empty($where)) return false;
        return DB::table(self::$table)->where($where)->first();
    }

    public static function planUpdate($where,$param){
        if(empty($param)) return false;
        return DB::table(self::$table)->where($where)->update($param);
    }

    public static function getAll($where=[]){
        return DB::table(self::$table)->where($where)->orderBy('id','ASC')->get();
    }




}