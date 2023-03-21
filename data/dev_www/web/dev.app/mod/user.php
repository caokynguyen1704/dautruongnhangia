<?php
/*-----------------------------------------------------+
 * 用户登录处理
 * @author yeahoo2000@gmail.com
 +-----------------------------------------------------*/
class user{
    // 生成图片验证码
    public static function captcha(){
        if(App::sess()->need_captcha){
            $code = Util::randString(4);
            App::sess()->captcha = $code;
            Util::captcha($code);
        }
    }
    // 登录页面
    public static function login(){
        // 准备数据
        $post = App::request()->get();
        //请求参数验证
        if(empty($post->sign) || empty($post->project_id) || empty($post->username) || empty($post->type)){
            exit('请求参数错误');
        }
        if($post->sign !== md5(App::cfg()->web_api->key . $post->project_id)){
            exit('验证错误');
        };
        //登录成功后的处理
        App::sess()->username = $post->username;
        App::sess()->group = $post->type;
        App::sess()->project_id = $post->project_id;
        App::sess()->sign = $post->sign;
        App::redirect(App::url('/release'));
    }
    // 注销帐号
    public static function logout(){
        unset(App::sess()->username);
        App::redirect(App::url('/'));
    }
    // 查询帐号信息
    private static function query($name){
        $users = App::cfg()->users;
        if(isset($users->$name)){
            return $users->$name;
        }
        return null;
    }
}
