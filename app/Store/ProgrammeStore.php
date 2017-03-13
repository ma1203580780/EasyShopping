<?php
namespace App\Store;

use Illuminate\Support\Facades\DB;

class ProgrammeStore
{
    //表名
    private static $table = 'data_programme';
    private static $limit = 10;

    public static function programmeInsert($param)
    {
        if (empty($param)) return false;
        return DB::table(self::$table)->insert($param);
    }

    public static function getFirst($where)
    {
        if (empty($where)) return false;
        return DB::table(self::$table)->where($where)->first();
    }

    public static function programmeUpdate($where, $param)
    {
        if (empty($param)) return false;
        return DB::table(self::$table)->where($where)->update($param);
    }

    public static function getAll($where = [])
    {
        return DB::table(self::$table)->where($where)->orderBy('id', 'ASC')->get();
    }

}


