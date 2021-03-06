<?php
/**
 * 
 * Banner (banner管理)
 *
 * @package      	GZPHP
 * @author          wen QQ:52009619 <admin@resonance.com.cn>
 * @copyright     	Copyright (c) 2008-2011  (http://www.resonance.com.cn)
 * @license         http://www.resonance.com.cn/license.txt
 * @version        	GzPHP v2.2 2013-01-04 resonance.com.cn $
 */
class BannerAction extends AdminbaseAction {

	protected $dao,$Type;
    function _initialize()
    {	
		parent::_initialize();
		$this->dao = M(MODULE_NAME);
		$this->Type=F('Banner');

    }

	public function index() {  
		if(APP_LANG)$map['lang']=array('eq',LANG_ID);
		$this->_list(MODULE_NAME, $map);
        $this->display();
    }

	public function _before_insert()
    {
		 if(APP_LANG)$_POST['lang']=LANG_ID;
	}
	public function edit() {
		$pos=strip_tags($_REQUEST['pos']);
		$name = MODULE_NAME;
		$model = M ( $name );
		$pk=ucfirst($model->getPk ());
		$id = $_REQUEST [$model->getPk ()];
		if(empty($id))   $this->error(L('do_empty'));
		if($pos){
			$map['pos']=array('eq',$pos);
			if(APP_LANG)$map['lang']=array('eq',LANG_ID);
			$vo = $model->where($map)->find();
		}else{
			$do='getBy'.$pk;
			$vo = $model->$do ( $id );
		}
		$gzphp_auth_key = sysmd5(C('ADMIN_ACCESS').$_SERVER['HTTP_USER_AGENT']);
		$gzphp_auth = authcode('1-1-0-10-jpeg,jpg,png,gif-5-230', 'ENCODE',$gzphp_auth_key);
		$this->assign('gzphp_auth',$gzphp_auth);
		if($vo['setup']) $vo['setup']=string2array($vo['setup']);
		$this->assign ( 'vo', $vo );
		$this->display ();
	}

	function delete(){
		$name = MODULE_NAME;
		$model = M ( $name );
		$pk = $model->getPk ();
		$id = $_REQUEST [$pk];
		if (isset ( $id )) {
			if(false!==$model->delete($id)){
				delattach(array('moduleid'=>'231','id'=>$id));
				$this->success(L('delete_ok'));
			}else{
				$this->error(L('delete_error').': '.$model->getDbError());
			}
		}else{
			$this->error (L('do_empty'));
		}
	}
}
?>