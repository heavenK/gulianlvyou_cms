
	//全局
	ROOT_URL = 'http://www.'+SITE_ROOT_NAME+'.com/';
	SERVER_URL = 'http://www.'+SERVER_ROOT_NAME+'.com/';
    B2CSERVICE_URL = ROOT_URL+"b2cservice/";
    SITE_URL = ROOT_URL;
	//常用定义
	SERVER_INDEX = SERVER_URL+'index.php?s=/';
	SITE_INDEX = B2CSERVICE_URL+'index.php?s=/';
	SITE_DATA = B2CSERVICE_URL+'Data/';
	SITE_PUBLIC = ROOT_URL+'demand/';
	__PUBLIC__ = B2CSERVICE_URL+'Public/';
	//获得服务器数据
    SERVER_GET_XIANLU = SERVER_INDEX+"Server/getxianlubyID";
	//订单流程填写订单地址
    BOOK_1_URL = SITE_INDEX+"Order/book1";
