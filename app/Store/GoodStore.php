<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2017/2/10
 * Time: 下午5:17
 */
namespace App\Store;

use Illuminate\Support\Facades\DB;

class GoodStore
{
    //表名
    private static $table = 'data_good';
    private static $limit = 20;

    public static function goodInsert($param){
        if(empty($param)) return false;
        return DB::table(self::$table)->insert($param);
    }

    public static function getFirst($where){
        if(empty($where)) return false;
        return DB::table(self::$table)->where($where)->first();
    }

    public static function goodUpdate($where,$param){
        if(empty($param)) return false;
        return DB::table(self::$table)->where($where)->update($param);
    }

    public static function getAll($page,$where=[]){
        //每页取几条数据
        $limit = self::$limit;
        //偏移量
        $start = ($page-1)*$limit;
        return DB::table(self::$table)->where($where)->orderBy('id','DESC')->skip($start)->take($limit)->get();
    }




}