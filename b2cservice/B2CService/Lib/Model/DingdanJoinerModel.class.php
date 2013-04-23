<?php

class DingdanJoinerModel extends Model {
	protected $trueTableName = 'b2cservice_member_dingdanjoiner';	
	
   // 自动验证设置 
    protected $_validate = array( 
        array('dingdanID', 'require', 'dingdanID不能为空！', 1,'',1), 
        array('name', 'require', 'name不能为空！', 1,'',1), 
        array('pinyin', 'require', 'pinyin不能为空！', 1,'',1), 
        array('zhengjiantype', 'require', 'zhengjiantype不能为空！', 1,'',1), 
        array('zhengjianhaoma', 'require', 'zhengjianhaoma不能为空！', 1,'',1), 
        array('birthday', 'require', 'birthday不能为空！', 1,'',1), 
        array('manorchild', 'require', 'manorchild不能为空！', 1,'',1), 
        array('sex', 'require', 'sex不能为空！', 1,'',1), 
    ); 
    // 自动填充设置 
    protected $_auto = array(
    ); 

}
?>