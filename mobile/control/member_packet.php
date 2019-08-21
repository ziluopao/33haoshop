<?php
/**
 * 我的红包
 *
 * @好商城V5.6 (c) 2005-2017 33hao Inc.
 * @license    http://www.33hao.com
 * @link       交流群号：216611541
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */


defined('In33hao') or exit('Access Invalid!');

class member_packetControl extends mobileMemberControl {

	public function __construct(){
		parent::__construct();
	}

    /**
     * 订单列表
     */
    public function indexOp() {
		$model = Model();

        $condition = array();

		$condition['member_id'] = $this->member_info['member_id'];

        $list = $model->table('red_packet_rec')->where($condition)->page(10)->order('id desc')->select();
		if(!empty($list)){
			foreach($list as $k=>$v){
				$list[$k]['packet_name']  = $v['packet_name'];
				$list[$k]['packet_price'] = $v['packet_price'];
				$list[$k]['valid_date']   = date("Y-m-d H:i:s", $v['valid_date']);
				$list[$k]['add_time']   = date("Y-m-d H:i:s", $v['add_time']);
				$list[$k]['is_use']       = $v['is_use'];
				/* if(!empty($list[$k]['use_time'])){
					$list[$k]['use_time']   = date("Y-m-d H:i:s", $v['use_time']);
				} */
			}
		}

        $page_count = $model->gettotalpage();

        output_data(array('list' => $list), mobile_page($page_count));
    }

    /**
     * 领取红包
     */
	public function getpackOp(){
		$model = Model();

		$id = intval(trim($_GET['id']));

		//查询用户信息
		$member_model = Model('member');
		$member = $member_model->getMemberInfo(array('member_id'=>$this->member_info['member_id']));
		if(empty($member)){
			output_error('该用户不存在');
		}

		//检查红包状态
		$packet = $model->table('red_packet')->where(array('id'=>$id))->find();
		if($packet['state']==2){
			output_error('该红包活动已结束');
		}
		if(time() > $packet['end_time']){
			output_error('该红包活动已结束');
		}
		if(time() < $packet['start_time']){
			output_error('该红包活动未开始');
		}
		if($packet['packet_number'] == $packet['packet_numbered']){
			output_error('手慢了，红包派送完了');
		}

		//判断该用户是否已经领取
		$rec = $model->table('red_packet_rec')->where(array('packet_id'=>$id,'member_id'=>$member['member_id']))->find();
		if(empty($rec)){
			//查询中奖几率
			$rand = rand(1,100);
			if($rand <= $packet['win_rate']){
				/**
				 * 中奖操作
				 */
				$win_rec = $model->table('red_packet_list')->where(array('packet_id'=>$packet['id']))->find();
				
				//增加会员预存款
				$member_where  = array('member_id'=>$member['member_id']);
				$member_update = array('available_predeposit'=>array('exp','available_predeposit+'.$win_rec['packet_price']));
				$member_model->editMember($member_where,$member_update);

				//添加预存款日志
				$pd_log = array(
					'lg_member_id'     => $member['member_id'],
					'lg_member_name'   => $member['member_name'],
					'lg_type'          => 'red_packet',
					'lg_av_amount'     => +$win_rec['packet_price'],
					'lg_freeze_amount' => 0,
					'lg_add_time'      => time(),
					'lg_desc'          => '用户参与红包活动：'.$packet['packet_name'],
				);
				$model->table('pd_log')->insert($pd_log);

				//添加用户领取红包记录
				if(!empty($packet['valid_date'])){
					$valid_date = $packet['valid_date'];
				}else{
					$a = date('Y-m-d',time());
					$b = strtotime($a.' 23:59:59');
					$valid_date = $b + 86400*($packet['valid_date2']-1);
				}

				$rec_log = array(
					'packet_id'     => $packet['id'],
					'packet_name'   => $packet['packet_name'],
					'member_id'     => $member['member_id'],
					'member_name'   => $member['member_name'],
					'packet_price'  => $win_rec['packet_price'],
					'add_time'      => time(),
					'valid_date'    => $valid_date,
					'is_use'        => 2,
				);
				$model->table('red_packet_rec')->insert($rec_log);

				//增加领取红包数量
				$model->table('red_packet')->where(array('id'=>$packet['id']))->update(array('packet_numbered'=>array('exp','packet_numbered+1')));

				//删除小红包记录
				$model->table('red_packet_list')->where(array('id'=>$win_rec['id']))->delete();

				//添加用户抽奖标记
				output_data(array('packet_price' => $win_rec['packet_price'],'is_packet'=>1, 'valid_date'=>date("Y-m-d H:i:s")));
			}else{
				$rec_log = array(
					'packet_id'     => $packet['id'],
					'packet_name'   => $packet['packet_name'],
					'member_id'     => $member['member_id'],
					'member_name'   => $member['member_name'],
					'packet_price'  => '0.00',
					'add_time'      => time(),
					'is_use'        => 1,
					'use_time'      => time(),
				);
				$model->table('red_packet_rec')->insert($rec_log);
				
				output_error('真可惜，红包与您擦肩而过~');
			}
		}else{
			output_error('您已参加过此次活动~');
		}

	}

}
