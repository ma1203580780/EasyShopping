<?php
/**
 * Created by PhpStorm.
 * User: machuang
 * Date: 2017/2/10
 * Time: 下午5:17
 */
namespace App\Store;

use Illuminate\Support\Facades\DB;

class CateStore
{
    //表名
    private static $table = 'data_cate';
    private static $limit = 10;

    /**
     * @param $param
     * @param $param
     * @return bool
     * 插入信息
     */
    public static function cateInsert($param){
        if(empty($param)) return false;
        return DB::table(self::$table)->insert($param);
    }

    /**
     * @param $where
     * @return bool
     */
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
    public static function userUpdate($where,$param){
        if(empty($param)) return false;
        return DB::table(self::$table)->where($where)->update($param);
    }

    /**
     * @param $where
     * @return bool
     */
    public static function getAll(){
        return DB::table(self::$table)->orderBy('id','DESC')->get();
    }




}