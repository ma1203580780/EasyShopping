<?php

namespace App\Service;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

/**
 * Class RedisTool
 * @package App\Http\Controllers\Tools
 * 提供各种操作redis不同类型的操作
 */
class RedisService
{

    /**
     * 有序集合增加
     * @param $key
     * @param $score
     * @param $member
     * @return mixed
     */
    public function sSetZadd($key, $score, $member)
    {
        return Redis::ZADD($key, $score, $member);
    }

    /**
     * 为有序集 key 的成员 member 的 score 值加上增量 increment
     * @param $key
     * @param $score
     * @param $member
     * @return mixed
     */
    public function IncreZincrby($key, $score, $member)
    {
        return Redis::ZINCRBY($key, $score, $member);
    }

    /**
     * 为有HASH key 的成员 member 的 score 值加上增量 increment
     * @param $key
     * @param $score
     * @param $member
     * @return mixed
     */
    public function IncreHincrby($key, $member, $score)
    {
        return Redis::HINCRBY($key, $member, $score);
    }

    /**
     * 为有HASH key 的成员 member 的 score 值加上增量 increment
     * @param $key
     * @param $score
     * @param $member
     * @return mixed
     */
    public function IncreHincrbyfloat($key, $member, $score)
    {
        return Redis::HINCRBYFLOAT($key, $member, $score);
    }

    /**
     * @param $key
     * @param $second
     * @return mixed
     * 设置key的过期时间
     */
    public function ExpireKey($key, $second)
    {
        return Redis::EXPIRE($key, $second);
    }

    /**
     * @param $member
     * @param $content
     * add to hash
     * 添加至hash
     */
    public function hashSet($member, $content)
    {
        return Redis::HMSET($member, $content);
    }

    /**
     * @param $key
     * @return mixed
     * 获取有序集合的长度
     */
    public function sSetLength($key)
    {
        return Redis::ZCARD($key);
    }

    /**
     * @param $key
     * @param $start
     * @param $end
     * @return mixed
     * 获取有序集合的序列
     */
    public function sSetGetRange($key, $start, $end)
    {
        return Redis::ZREVRANGE($key, (int)$start, (int)$end);

    }

    /**
     * @param $key
     * @return mixed
     * 设置hash
     */
    public function hashGet($key)
    {
        return Redis::HGETALL($key);
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     * 设置String类型的key
     */
    public function stringSet($key, $value)
    {
        return Redis::SET($key,$value);
    }

    /**
     * @param $key
     * @return mixed
     * 获取String类型的key
     */
    public function stringGet($key)
    {
        return Redis::GET($key);
    }

    /**
     * @param $key
     * @return mixed
     * 获取Strig的长度
     */
    public function stringLength($key){
        return Redis::STRLEN($key);
    }



    /**
     * @param $start
     * @param $end
     * @param $key
     * @return mixed
     * 获取list长度
     */
    public function getRangeList($start, $end, $key)
    {
        return Redis::LRANGE($key, $start, $end);
    }

    /**
     * @param $key
     * @return mixed
     * 获取redis列表长度
     */
    public function getRangeLength($key)
    {
        return Redis::LLEN($key);
    }

    /**
     * @param $key
     * @param $value
     * 设置key
     */
    public function setKey($key, $value)
    {
        Redis::set($key, $value);
    }

    /**
     * @param $key
     * @return mixed
     * 判断key是否存在
     */
    public function existsKey($key)
    {
        return Redis::EXISTS($key);
    }

    /**
     * @param $key
     * @return mixed
     * 获取key
     */
    public function getKey($key)
    {
        return Redis::get($key);
    }

    /**
     * @param $key
     * @return mixed
     * 删除key
     */
    public function delKey($key)
    {
        Log::info('prepare to del: ' . $key);
        return Redis::del($key);
    }

    /**
     * @param $key
     * @return mixed
     * 跑团，个人排行
     */
    public function rankScore($value='group_week_rank',$start=0,$stop=-1)
    {
        if(empty($value)) return false;
        $data = Redis::ZREVRANGE($value,$start, $stop, 'WITHSCORES');
        return $data;
    }

    /**
     * @param $start
     * @param $stop
     * @return mixed
     * 我的跑团
     */
    public function myGroupRedis($value='', $start=0, $stop= -1)
    {
        if(empty($value)) return false;
        $data = Redis::ZREVRANGE($value, $start, $stop, 'WITHSCORES');
        return $data;
    }


    /**
     * @param $key
     * @return mixed
     * 判断hash key（用户个人信息）是否存在
     */
    public function hashExist($value='')
    {
        if(empty($value)) return false;
        return  Redis::HKEYS($value);
    }

    /**
     * @param $key
     * @return mixed
     * 获取用户信息
     */
    public function getHashInfo($key_name,$field_name)
    {
        return Redis::HGET($key_name,$field_name);
    }

    /**
     * @param $key
     * @param array $key
     * @return mixed
     * 获取用户多个信息
     */
    public function getMHashInfo($key_name,$field_name)
    {
        return Redis::HMGET($key_name,$field_name);
    }

    /**
     * @param $key_name 键名
     * @param $field_name 领域名
     * @param $value 值
     * 更新哈希缓存值
     */
    public function updateHset($key_name, $filed_name, $value)
    {
        return Redis::Hset($key_name, $filed_name, $value);
    }

    /**
     * 删除有序集合中指定成员（一个或多个）
     *
     * @param $key_name 键名
     * @param $member 成员名
     */
    public function delZrem($key_name, $member)
    {
        return Redis::Zrem($key_name, $member);
    }
}
