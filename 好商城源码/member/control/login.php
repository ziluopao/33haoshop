<?php
/**
 * 前台登录 退出操作
 */



defined('In33hao') or exit('Access Invalid!');

class loginControl extends BaseLoginControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 登录操作
     *
     */
    public function indexOp(){
        Language::read("home_login_index,home_login_register");
        $lang   = Language::getLangContent();
        $model_member   = Model('member');
        //检查登录状态
        $model_member->checkloginMember();
        if ($_GET['inajax'] == 1 && C('captcha_status_login') == '1'){
            $script = "document.getElementById('codeimage').src='index.php?act=seccode&op=makecode&nchash=".getNchash()."&t=' + Math.random();";
        }
        $result = chksubmit(false,C('captcha_status_login'),'num');
        if ($result !== false){
            if ($result === -11){
                showDialog($lang['login_index_login_illegal'],'','error',$script);
            }elseif ($result === -12){
                showDialog($lang['login_index_wrong_checkcode'],'','error',$script);
            }
	    
	    
            
            $login_info = array();
            $login_info['user_name'] = $_POST['user_name'];
            $login_info['password'] = $_POST['password'];
            $member_info = $model_member->login($login_info);
            if(isset($member_info['error'])) {
                showDialog($member_info['error'],'','error',$script);
            }

            // 自动登录
            $member_info['auto_login'] = $_POST['auto_login'];
            $model_member->createSession($member_info, true);
            if ($_GET['inajax'] == 1){
                showDialog('',$_POST['ref_url'] == '' ? 'reload' : $_POST['ref_url'],'js');
            } else {
                redirect($_POST['ref_url']);
            }
        }else{

            //登录表单页面
            $_pic = @unserialize(C('login_pic'));
            if ($_pic[0] != ''){
                Tpl::output('lpic',UPLOAD_SITE_URL_HTTPS.'/'.ATTACH_LOGIN.'/'.$_pic[array_rand($_pic)]);
            }else{
                Tpl::output('lpic',UPLOAD_SITE_URL_HTTPS.'/'.ATTACH_LOGIN.'/'.rand(1,4).'.jpg');
            }

            if(empty($_GET['ref_url'])) {
                $ref_url = getReferer();
                if (!preg_match('/act=login&op=logout/', $ref_url)) {
                 $_GET['ref_url'] = $ref_url;
                }
            }
            Tpl::output('html_title',C('site_name').' - '.$lang['login_index_login']);
            if ($_GET['inajax'] == 1){
                Tpl::showpage('login_inajax','null_layout');
            }else{
                Tpl::showpage('login');
            }
        }
    }

    /**
     * 退出操作
     *
     * @param int $id 记录ID
     * @return array $rs_row 返回数组形式的查询结果
     */
    public function logoutOp(){
        Language::read("home_login_index");
        $lang   = Language::getLangContent();
        // 清理COOKIE
        setNcCookie('msgnewnum'.$_SESSION['member_id'],'',-3600);
        setNcCookie('auto_login', '', -3600);
        setNcCookie('cart_goods_num','',-3600);
        session_unset();
        session_destroy();
        if(empty($_GET['ref_url'])){
            $ref_url = getReferer();
        }else {
            $ref_url = $_GET['ref_url'];
        }
        redirect(LOGIN_SITE_URL . '/index.php?act=login&ref_url='.urlencode($ref_url));
    }

    /**
     * 会员注册页面
     *
     * @param
     * @return
     */
    public function registerOp() {
        Language::read("home_login_register");
        $lang   = Language::getLangContent();
        $model_member   = Model('member');
        $model_member->checkloginMember();
        Tpl::output('html_title',C('site_name').' - '.$lang['login_register_join_us']);
        Tpl::showpage('register');
    }

    /**
     * 会员添加操作
     *
     * @param
     * @return
     */
    public function usersaveOp() {
        //重复注册验证
        if (process::islock('reg')){
            showDialog(Language::get('nc_common_op_repeat'));
        }
        Language::read("home_login_register");
        $lang   = Language::getLangContent();
        $model_member   = Model('member');
        $model_member->checkloginMember();
        $result = chksubmit(true,C('captcha_status_register'),'num');
        if ($result){
            if ($result === -11){
                showDialog($lang['invalid_request'],'','error');
            }elseif ($result === -12){
                showDialog($lang['login_usersave_wrong_code'],'','error');
            }
        } else {
            showDialog($lang['invalid_request'],'','error');
        }
		//33Hao 5.2 分销 会员邀请
		$invite_id = intval(base64_decode($_COOKIE['uid']))/1;
		if(!empty($invite_id)) {
		    $member=$model_member->getMemberInfo(array('member_id'=>$invite_id));
			$invite_one = $invite_id;
			$invite_two = $member['invite_one'];
			$invite_three = $member['invite_two'];
		
		}else{
		    $invite_one = 0;
			$invite_two = 0;
			$invite_three = 0;
		
		}
        $register_info = array();
        $register_info['username'] = $_POST['user_name'];
        $register_info['password'] = $_POST['password'];
        $register_info['password_confirm'] = $_POST['password_confirm'];
        $register_info['email'] = $_POST['email'];
		//添加奖励积分ID BY 33HAO.COM
		$register_info['inviter_id'] = intval(base64_decode($_COOKIE['uid']))/1;
		//33Hao 5.2 分销
		$register_info['invite_one'] = $invite_one;
		$register_info['invite_two'] = $invite_two;
		$register_info['invite_three'] = $invite_three;
		
        $member_info = $model_member->register($register_info);
        if(!isset($member_info['error'])) {
            $model_member->createSession($member_info,true);
            process::addprocess('reg');

            $_POST['ref_url']   = (strstr($_POST['ref_url'],'logout')=== false && !empty($_POST['ref_url']) ? $_POST['ref_url'] : urlMember('member_information', 'member'));
            if ($_GET['inajax'] == 1){
                showDialog('',$_POST['ref_url'] == '' ? 'reload' : $_POST['ref_url'],'js');
            } else {
                redirect($_POST['ref_url']);
            }
        } else {
            showDialog($member_info['error']);
        }
    }
    /**
     * 会员名称检测
     *
     * @param
     * @return
     */
    public function check_memberOp() {
            /**
            * 实例化模型
            */
            $model_member   = Model('member');

            $check_member_name  = $model_member->getMemberInfo(array('member_name'=>$_GET['user_name']));
            if(is_array($check_member_name) && count($check_member_name)>0) {
                echo 'false';
            } else {
                echo 'true';
            }
    }

    /**
     * 电子邮箱检测
     *
     * @param
     * @return
     */
    public function check_emailOp() {
        $model_member = Model('member');
        $check_member_email = $model_member->getMemberInfo(array('member_email'=>$_GET['email']));
        if(is_array($check_member_email) && count($check_member_email)>0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    /**
     * 忘记密码页面
     */
    public function forget_passwordOp(){
        /**
         * 读取语言包
         */
        Language::read('home_login_register');
        $_pic = @unserialize(C('login_pic'));
        if ($_pic[0] != ''){
            Tpl::output('lpic',UPLOAD_SITE_URL_HTTPS.'/'.ATTACH_LOGIN.'/'.$_pic[array_rand($_pic)]);
        }else{
            Tpl::output('lpic',UPLOAD_SITE_URL_HTTPS.'/'.ATTACH_LOGIN.'/'.rand(1,4).'.jpg');
        }
        Tpl::output('html_title',C('site_name').' - '.Language::get('login_index_find_password'));
        Tpl::showpage('find_password');
    }

    /**
     * 找回密码的发邮件处理
     */
    public function find_passwordOp(){
        Language::read('home_login_register');
        $lang   = Language::getLangContent();

        $result = chksubmit(true,true,'num');
        if ($result !== false){
            if ($result === -11){
                showDialog('非法提交','','error');
            }elseif ($result === -12){
                showDialog('验证码错误','','error');
            }
        }

        if(empty($_POST['username'])){
            showDialog($lang['login_password_input_username'],'','error');
        }

        if (process::islock('forget')) {
            showDialog($lang['nc_common_op_repeat'],'','error');
        }

        $member_model   = Model('member');
        $member = $member_model->getMemberInfo(array('member_name'=>$_POST['username']));
        if(empty($member) or !is_array($member)){
            process::addprocess('forget');
            showDialog($lang['login_password_username_not_exists'],'','error');
        }

        if(empty($_POST['email'])){
            showDialog($lang['login_password_input_email'],'','error');
        }

        if(strtoupper($_POST['email'])!=strtoupper($member['member_email'])){
            process::addprocess('forget');
            showDialog($lang['login_password_email_not_exists'],'','error');
        }
        process::clear('forget');
        //产生密码
        $new_password   = random(15);
        if(!($member_model->editMember(array('member_id'=>$member['member_id']),array('member_passwd'=>md5($new_password))))){
            showDialog($lang['login_password_email_fail'],'','error');
        }

        $model_tpl = Model('mail_templates');
        $tpl_info = $model_tpl->getTplInfo(array('code'=>'reset_pwd'));
        $param = array();
        $param['site_name'] = C('site_name');
        $param['user_name'] = $_POST['username'];
        $param['new_password'] = $new_password;
        $param['site_url'] = SHOP_SITE_URL;
        $subject    = ncReplaceText($tpl_info['title'],$param);
        $message    = ncReplaceText($tpl_info['content'],$param);

        $email	= new Email();
		$result	= $email->send_sys_email($_POST["email"],$subject,$message);
        showDialog('新密码已经发送至您的邮箱，请尽快登录并更改密码！','','succ','',5);
    }

    /**
     * 邮箱绑定验证
     */
    public function bind_emailOp() {
       $model_member = Model('member');
       $uid = @base64_decode($_GET['uid']);
       $uid = decrypt($uid,'');
       list($member_id,$member_email) = explode(' ', $uid);

       if (!is_numeric($member_id)) {
           showMessage('验证失败',SHOP_SITE_URL,'html','error');
       }

       $member_info = $model_member->getMemberInfo(array('member_id'=>$member_id),'member_email');
       if ($member_info['member_email'] != $member_email) {
           showMessage('验证失败',SHOP_SITE_URL,'html','error');
       }

       $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$member_id));
       if (empty($member_common_info) || !is_array($member_common_info)) {
           showMessage('验证失败',SHOP_SITE_URL,'html','error');
       }
       if (md5($member_common_info['auth_code']) != $_GET['hash'] || TIMESTAMP - $member_common_info['send_acode_time'] > 24*3600) {
           showMessage('验证失败',SHOP_SITE_URL,'html','error');
       }

       $update = $model_member->editMember(array('member_id'=>$member_id),array('member_email_bind'=>1));
       if (!$update) {
           showMessage('系统发生错误，如有疑问请与管理员联系',SHOP_SITE_URL,'html','error');
       }

       $data = array();
       $data['auth_code'] = '';
       $data['send_acode_time'] = 0;
       $update = $model_member->editMemberCommon($data,array('member_id'=>$_SESSION['member_id']));
       if (!$update) {
           showDialog('系统发生错误，如有疑问请与管理员联系');
       }
       showMessage('邮箱设置成功','index.php?act=member_security&op=index');

    }
}
