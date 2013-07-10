<?php

class GexingdingzhiModel extends RelationModel {
	protected $trueTableName = 'b2cservice_member_gexingdingzhi';	
	protected $pk = 'mid';	
	
	protected $_link = array(
	
	);
    // 自动填充设置 
    protected $_auto = array( 
        array('time','time',1,'function'),
    ); 
   
   // 自动验证设置 
    protected $_validate = array( 
    ); 
	
	
	

}
?>