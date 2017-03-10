<?php
namespace App\Services;


use Illuminate\Support\Facades\Mail;

class mailService
{
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
    public static function sendEmail2($name, $to, $title, $data, $blade = 'register')
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
}
