<?php   if(!defined('DEDEINC')) exit("Request Error!");
/**
 * 搜索视图类
 *
 * @version        $Id: arc.searchview.class.php 1 15:26 2010年7月7日Z tianya $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(DEDEINC."/typelink.class.php");
require_once(DEDEINC."/dedetag.class.php");
require_once(DEDEINC."/splitword.class.php");
require_once(DEDEINC."/taglib/hotwords.lib.php");
require_once(DEDEINC."/taglib/channel.lib.php");
require_once(DEDEINC."/taglib/arclist.lib.php");
require_once(DEDEINC."/taglib/channelartlist.lib.php");
require_once(DEDEINC."/taglib/myad.lib.php");
require_once(DEDEINC."/taglib/php.lib.php");
require_once(DEDEINC."/taglib/sql.lib.php");
require_once(DEDEINC."/taglib/channel.lib.php");

@set_time_limit(0);
@ini_set('memory_limit', '512M');

/**
 * 搜索视图类
 *
 * @package          SearchView
 * @subpackage       DedeCMS.Libraries
 * @link             http://www.dedecms.com
 */
class SearchView
{
    var $dsql;
    var $dtp;
    var $dtp2;
    var $TypeID;
    var $TypeLink;
    var $PageNo;
    var $TotalPage;
    var $TotalResult;
    var $PageSize;
    var $ChannelType;
    var $TempInfos;
    var $Fields;
    var $PartView;
    var $StartTime;
    var $Keywords;
    var $OrderBy;
    var $SearchType;
    var $mid;
    var $KType;
    var $Keyword;
    var $SearchMax;
    var $SearchMaxRc;
    var $SearchTime;
    var $AddSql;
    var $RsFields;
    var $Sphinx;
	var $xianlu;//add
	var $mudidi;//add
	var $chufadi;//add
	var $tianshu;//add
	var $jiage;//add
	var $xianluid;//add
	var $order_flag;//add
	var $order_jiage;//add
	var $order_tianshu;//add
	var $order_rm;//add
	var $order_tj;//add

    /**
     *  php5构造函数
     *
     * @access    public
     * @param     int  $typeid  栏目ID
     * @param     string  $keyword  关键词
     * @param     string  $orderby  排序
     * @param     string  $achanneltype  频道类型
     * @param     string  $searchtype  搜索类型
     * @param     string  $starttime  开始时间
     * @param     string  $upagesize  页数
     * @param     string  $kwtype  关键词类型
     * @param     string  $mid  会员ID
     * @return    string
     */
    function __construct($typeid,$keyword,$orderby,$achanneltype="all",
    $searchtype='',$starttime=0,$upagesize=20,$kwtype=1,$mid=0,$xianlu,$mudidi,$tianshu,$jiage,$order_flag,$order_jiage,$order_tianshu,$order_rm,$order_tj)//add $xianlu,$mudidi
    {
        global $cfg_search_max,$cfg_search_maxrc,$cfg_search_time,$cfg_sphinx_article;
        if(empty($upagesize))
        {
            $upagesize = 8;
        }
        $this->TypeID = $typeid;
        $this->Keyword = $keyword;
        $this->OrderBy = $orderby;
        $this->KType = $kwtype;
        $this->PageSize = (int)$upagesize;
        $this->StartTime = $starttime;
        $this->ChannelType = $achanneltype;
        $this->SearchMax = $cfg_search_max;
        $this->SearchMaxRc = $cfg_search_maxrc;
        $this->SearchTime = $cfg_search_time;
        $this->mid = $mid;
        $this->RsFields = '';
        $this->SearchType = $searchtype=='' ? 'titlekeyword' : $searchtype;
        $this->dsql = $GLOBALS['dsql'];
        $this->dtp = new DedeTagParse();
        $this->dtp->SetRefObj($this);
        $this->dtp->SetNameSpace("dede","{","}");
        $this->dtp2 = new DedeTagParse();
        $this->dtp2->SetNameSpace("field","[","]");
        $this->TypeLink = new TypeLink($typeid);
		//add by ks
		$this->xianlu = $xianlu;
		$this->mudidi = $mudidi;
		$this->tianshu = $tianshu;
		$this->jiage = $jiage;
		$this->xianluid = GetSonIds("25,26,18");
		$this->order_flag = $order_flag;
		$this->order_jiage = $order_jiage;
		$this->order_tianshu = $order_tianshu;
		$this->order_rm = $order_rm;
		$this->order_tj = $order_tj;
		//end add
        // 通过分词获取关键词
        $this->Keywords = $this->GetKeywords($keyword);
		
		//add by ks
		function ips($ip){ 
			$str = file_get_contents("http://int.dpool.sina.com.cn/iplookup/iplookup.php?ip={$ip}"); 
			$str = iconv("GBK", "UTF-8", $str);
			$arr = explode("	",$str); 
			return $arr;
		}
		$user_address = ips(GetIP());
		$this->chufadi = $user_address[5];
		if(empty($this->chufadi)){
			$this->chufadi = "大连";
		}
		
		
		//
		$mudidi_n = NULL;
		$mudidi_text = "";
		$mudidi_query = "SELECT act.mudidi FROM `#@__archives` arc LEFT JOIN `cty_addon7` act ON arc.id=act.aid WHERE arc.typeid IN (25,27,28,37,38,29,39,40,31,41,42,101,102,103,26,99,30,43,44,104,105,106,18,68,71,72,66,67,69,70) AND act.chufachengshi='".$this->chufadi."' AND arc.channel='7' AND arc.arcrank > -1 AND arc.ismake <> 0";
        $this->dsql->SetQuery($mudidi_query);
        $this->dsql->Execute();
		while($mudidi_row = $this->dsql->getarray()){
			//var_dump($mudidi_row);
			foreach($mudidi_row as $w){
				$temp_m = explode(',',$w);
				foreach($temp_m as $m){
					if($mudidi_n == NULL){
						$mudidi_n[0]['count'] = 1;
						$mudidi_n[0]['mudidi'] = $m;
						continue;
					}
					$i = 0;
					$mark = 0;
					foreach($mudidi_n as $vol){
						if($m == $vol['mudidi']){
							$mudidi_n[$i]['count'] += 1;
							$mark = 1;
						}
						$i++;
					}
					if($mark == 0){
						$j = count($mudidi_n);
						$mudidi_n[$j]['count'] = 1;
						$mudidi_n[$j]['mudidi'] = $m;
					}
				}
			}
		}
		foreach($mudidi_n as $m){
			if($m['mudidi'] == $mudidi){
				$this->mudidi_text .= '<a href="/plus/gl_list.php?q='.$q.'&searchtype=title&channeltype=7&kwtype=0&xianlu='.$xianlu.'&mudidi='.$m['mudidi'].'&tianshu='.$tianshu.'&jiage='.$jiage.'" class="list_term_btn">'.$m['mudidi'].'（'.$m['count'].'）</a>';
			}else{
				$this->mudidi_text .= '<a href="/plus/gl_list.php?q='.$q.'&searchtype=title&channeltype=7&kwtype=0&xianlu='.$xianlu.'&mudidi='.$m['mudidi'].'&tianshu='.$tianshu.'&jiage='.$jiage.'">'.$m['mudidi'].'（'.$m['count'].'）</a>';
			}
		}
		//
		
		
//		$mudidi_text = "";
//		$mudidi_query = "SELECT DISTINCT act.mudidi FROM `#@__archives` arc LEFT JOIN `cty_addon7` act ON arc.id=act.aid WHERE act.chufachengshi='".$this->chufadi."' AND arc.arcrank > -1 AND arc.ismake <> 0";
//        $this->dsql->SetQuery($mudidi_query);
//        $this->dsql->Execute();
//		while($mudidi_row = $this->dsql->getarray()){
//			$mudidi_query = "SELECT arc.*,act.*
//				FROM `#@__archives` arc LEFT JOIN `cty_addon7` act ON arc.id=act.aid
//				WHERE arc.typeid IN (".$this->xianluid.") AND arc.channel='7' AND act.chufachengshi='".$this->chufadi."' AND act.mudidi='".$mudidi_row['mudidi']."' AND arc.arcrank > -1 AND arc.ismake <> 0";
//				//var_dump($mudidi_query);
//			$mudidi_num = $this->dsql->ExecuteNoneQuery2($mudidi_query);
//			if($mudidi_row['mudidi'] == $mudidi){
//				$this->mudidi_text .= '<a href="/plus/gl_list.php?q='.$q.'&searchtype=title&channeltype=7&kwtype=0&xianlu='.$xianlu.'&mudidi='.$mudidi_row['mudidi'].'&tianshu='.$tianshu.'&jiage='.$jiage.'" class="list_term_btn">'.$mudidi_row['mudidi'].'（'.$mudidi_num.'）</a>';
//			}else{
//				$this->mudidi_text .= '<a href="/plus/gl_list.php?q='.$q.'&searchtype=title&channeltype=7&kwtype=0&xianlu='.$xianlu.'&mudidi='.$mudidi_row['mudidi'].'&tianshu='.$tianshu.'&jiage='.$jiage.'">'.$mudidi_row['mudidi'].'（'.$mudidi_num.'）</a>';
//			}
//		}
		
		
		
		//end add
        //设置一些全局参数的值
		//edit by ks
        /*if($this->TypeID=="0"){
            $this->ChannelTypeid=1;
        }else{
            $row =$this->dsql->GetOne("SELECT channeltype FROM `#@__arctype` WHERE id={$this->TypeID}");
            $this->ChannelTypeid=$row['channeltype'];
        }*/
		//end edit
		//add by ks
		//var_dump($xianlu);
		$row =$this->dsql->GetOne("SELECT channeltype FROM `#@__arctype` WHERE id IN ({$this->xianlu})");
        $this->ChannelTypeid=$row['channeltype'];
		//end add
        foreach($GLOBALS['PubFields'] as $k=>$v)
        {
            $this->Fields[$k] = $v;
        }
        if ($cfg_sphinx_article == 'Y')
        {
            // 初始化sphinx
            $this->sphinx = new SphinxClient;
            
            $mode = SPH_MATCH_EXTENDED2;            //匹配模式
            $ranker = SPH_RANK_PROXIMITY_BM25; //统计相关度计算模式，仅使用BM25评分计算
            $this->sphinx->SetServer($GLOBALS['cfg_sphinx_host'], $GLOBALS['cfg_sphinx_port']);
            $this->sphinx->SetArrayResult(true);
            $this->sphinx->SetMatchMode($mode);
            $this->sphinx->SetRankingMode($ranker);
            
            $this->CountRecordSphinx();
        } else {
            $this->CountRecord();
        }
        
        
        $tempfile = $GLOBALS['cfg_basedir'].$GLOBALS['cfg_templets_dir']."/".$GLOBALS['cfg_df_style']."/search.htm";
        if(!file_exists($tempfile)||!is_file($tempfile))
        {
            echo "模板文件不存在，无法解析！";
            exit();
        }
        $this->dtp->LoadTemplate($tempfile);
        $this->TempInfos['tags'] = $this->dtp->CTags;
        $this->TempInfos['source'] = $this->dtp->SourceString;
        if($this->PageSize=="")
        {
            $this->PageSize = 20;
        }
        $this->TotalPage = ceil($this->TotalResult/$this->PageSize);
        if($this->PageNo==1)
        {
            $this->dsql->ExecuteNoneQuery("UPDATE `#@__search_keywords` SET result='".$this->TotalResult."' WHERE keyword='".addslashes($keyword)."'; ");
        }
    
    }

    //php4构造函数
    function SearchView($typeid,$keyword,$orderby,$achanneltype="all",
    $searchtype="",$starttime=0,$upagesize=20,$kwtype=1,$mid=0,$mudidi,$tianshu,$jiage,$order_flag,$order_jiage,$order_tianshu,$order_rm,$order_tj)//add $xianlu,$mudidi
    {
        $this->__construct($typeid,$keyword,$orderby,$achanneltype,$searchtype,$starttime,$upagesize,$kwtype,$mid,$mudidi,$tianshu,$jiage,$order_flag,$order_jiage,$order_tianshu,$order_rm,$order_tj);//add $xianlu,$mudidi
    }

    //关闭相关资源
    function Close()
    {
    }

    /**
     *  获得关键字的分词结果，并保存到数据库
     *
     * @access    public
     * @param     string  $keyword  关键词
     * @return    string
     */
    function GetKeywords($keyword)
    {
        global $cfg_soft_lang;
        $keyword = cn_substr($keyword, 50);
        $row = $this->dsql->GetOne("SELECT spwords FROM `#@__search_keywords` WHERE keyword='".addslashes($keyword)."'; ");
        if(!is_array($row))
        {
            if(strlen($keyword)>7)
            {
                $sp = new SplitWord($cfg_soft_lang, $cfg_soft_lang);
                $sp->SetSource($keyword, $cfg_soft_lang, $cfg_soft_lang);
                $sp->SetResultType(2);
                $sp->StartAnalysis(TRUE);
                $keywords = $sp->GetFinallyResult();
                $idx_keywords = $sp->GetFinallyIndex();
                ksort($idx_keywords);
                $keywords = $keyword.' ';
                foreach ($idx_keywords as $key => $value) {
                    if (strlen($key) <= 3) {
                        continue;
                    }
                    $keywords .= ' '.$key;
                }
                $keywords = preg_replace("/[ ]{1,}/", " ", $keywords);
                //var_dump($idx_keywords);exit();
                unset($sp);
            }
            else
            {
                $keywords = $keyword;
            }
            $inquery = "INSERT INTO `#@__search_keywords`(`keyword`,`spwords`,`count`,`result`,`lasttime`)
          VALUES ('".addslashes($keyword)."', '".addslashes($keywords)."', '1', '0', '".time()."'); ";
            $this->dsql->ExecuteNoneQuery($inquery);
        }
        else
        {
            $this->dsql->ExecuteNoneQuery("UPDATE `#@__search_keywords` SET count=count+1,lasttime='".time()."' WHERE keyword='".addslashes($keyword)."'; ");
            $keywords = $row['spwords'];
        }
        return $keywords;
    }

    /**
     *  获得关键字SQL
     *
     * @access    private
     * @return    string
     */
    function GetKeywordSql()
    {
        $ks = explode(' ',$this->Keywords);
        $kwsql = '';
        $kwsqls = array();
        foreach($ks as $k)
        {
            $k = trim($k);
            if(strlen($k)<1)
            {
                continue;
            }
            if(ord($k[0])>0x80 && strlen($k)<2)
            {
                continue;
            }
            $k = addslashes($k);
            if($this->ChannelType < 0 || $this->ChannelTypeid < 0){
                $kwsqls[] = " arc.title LIKE '%$k%' ";
            }else{
                if($this->SearchType=="title"){
                    $kwsqls[] = " arc.title LIKE '%$k%' ";
                }else{
                    $kwsqls[] = " CONCAT(arc.title,' ',arc.writer,' ',arc.keywords) LIKE '%$k%' ";
                }
            }
        }
        if(!isset($kwsqls[0]))
        {
            return '';
        }
        else
        {
            if($this->KType==1)
            {
                $kwsql = join(' OR ',$kwsqls);
            }
            else
            {
                $kwsql = join(' And ',$kwsqls);
            }
            return $kwsql;
        }
    }

    /**
     *  获得相关的关键字
     *
     * @access    public
     * @param     string  $num  关键词数目
     * @return    string
     */
    function GetLikeWords($num=8)
    {
        $ks = explode(' ',$this->Keywords);
        $lsql = '';
        foreach($ks as $k)
        {
            $k = trim($k);
            if(strlen($k)<2)
            {
                continue;
            }
            if(ord($k[0])>0x80 && strlen($k)<2)
            {
                continue;
            }
            $k = addslashes($k);
            if($lsql=='')
            {
                $lsql = $lsql." CONCAT(spwords,' ') LIKE '%$k %' ";    
            }else{
                $lsql = $lsql." OR CONCAT(spwords,' ') LIKE '%$k %' ";
            }
        }
        if($lsql=='')
        {
            return '';
        }
        else
        {
            $likeword = '';
            $lsql = "(".$lsql.") AND NOT(keyword like '".addslashes($this->Keyword)."') ";
            $this->dsql->SetQuery("SELECT keyword,count FROM `#@__search_keywords` WHERE $lsql ORDER BY lasttime DESC LIMIT 0,$num; ");
            $this->dsql->Execute('l');
            while($row=$this->dsql->GetArray('l'))
            {
                if($row['count']>1000)
                {
                    $fstyle=" style='font-size:11pt;color:red'";
                }
                else if($row['count']>300)
                {
                    $fstyle=" style='font-size:10pt;color:green'";
                }
                else
                {
                    $style = "";
                }
                $likeword .= "　<a href='search.php?keyword=".urlencode($row['keyword'])."&searchtype=titlekeyword'".$style."><u>".$row['keyword']."</u></a> ";
            }
            return $likeword;
        }
    }

    /**
     *  加粗关键字
     *
     * @access    private
     * @param     string  $fstr  关键词字符
     * @return    string
     */
    function GetRedKeyWord($fstr)
    {
        //echo $fstr;
        $ks = explode(' ',$this->Keywords);
        foreach($ks as $k)
        {
            $k = trim($k);
            if($k=='')
            {
                continue;
            }
            if(ord($k[0])>0x80 && strlen($k)<2)
            {
                continue;
            }
            // 这里不区分大小写进行关键词替换
            $fstr = str_ireplace($k, "$k", $fstr);
            // 速度更快,效率更高
            //$fstr = str_replace($k, "<font color='red'>$k</font>", $fstr);
        }
        return $fstr;
    }
    
    // Sphinx记录统计
    function CountRecordSphinx()
    {
        $this->TotalResult = -1;
        if(isset($GLOBALS['TotalResult']))
        {
            $this->TotalResult = $GLOBALS['TotalResult'];
            $this->TotalResult = is_numeric($this->TotalResult)? $this->TotalResult : "";
        }
        if(isset($GLOBALS['PageNo']))
        {
            $this->PageNo = intval($GLOBALS['PageNo']);
        }
        else
        {
            $this->PageNo = 1;
        }
        
        if($this->StartTime > 0)
        {
            $this->sphinx->SetFilterRange('senddate', $this->StartTime, time(), false);
        }
		/*if($this->TypeID > 0)
        {
            $this->sphinx->SetFilter('typeid', GetSonIds($this->TypeID));
        }*/
        if($this->xianlu)
        {
            $this->sphinx->SetFilter('typeid', GetSonIds($this->xianlu));
        }
        $this->sphinx->SetFilter('channel', array(1));
        if($this->mid > 0)
        {
            $this->sphinx->SetFilter('mid', $this->mid);
        }
        //$this->sphinx->SetFilterRange('arcrank', -1, 100, false);
        // var_dump($this->sphinx);exit;
        $res = array();
        $res = AutoCharset($this->sphinx->Query($this->Keywords, 'mysql, delta'), 'utf-8', 'gbk');
        
        $this->TotalResult = $res['total'];
    }

    /**
     *  统计列表里的记录
     *
     * @access    public
     * @return    string
     */
    function CountRecord()
    {
        $this->TotalResult = -1;
        if(isset($GLOBALS['TotalResult']))
        {
            $this->TotalResult = $GLOBALS['TotalResult'];
            $this->TotalResult = is_numeric($this->TotalResult)? $this->TotalResult : "";
        }
        if(isset($GLOBALS['PageNo']))
        {
            $this->PageNo = intval($GLOBALS['PageNo']);
        }
        else
        {
            $this->PageNo = 1;
        }
        $ksql = $this->GetKeywordSql();
        $ksqls = array();
        if($this->StartTime > 0)
        {
            $ksqls[] = " arc.senddate>'".$this->StartTime."' ";
        }
		/*if($this->TypeID > 0)
        {
            $ksqls[] = " typeid IN (".GetSonIds($this->TypeID).") ";
        }*/
        if($this->xianlu)
        {
			if($this->xianlu == "25,26,18"){
				$ksqls[] = " typeid IN (".GetSonIds("25").",".GetSonIds("26").",".GetSonIds("18").") ";
			}elseif($this->xianlu == "25,26"){
				$ksqls[] = " typeid IN (".GetSonIds("25").",".GetSonIds("26").") ";
			}else{
				$ksqls[] = " typeid IN (".GetSonIds($this->xianlu).") ";
			}
        }
        if($this->ChannelType > 0)
        {
            $ksqls[] = " arc.channel='".$this->ChannelType."'";
        }
        if($this->mid > 0)
        {
            $ksqls[] = " arc.mid = '".$this->mid."'";
        }
		//add by ks
		if($this->chufadi)
        {
            $ksqls[] = " act.chufachengshi = '".$this->chufadi."'";
        }
		if($this->mudidi)
        {
            $ksqls[] = " act.mudidi LIKE '%".$this->mudidi."%'";
        }
		if($this->order_flag)
        {
            $ksqls[] = " (arc.flag LIKE '%a%' OR arc.flag LIKE '%h%' OR arc.flag LIKE '%c%') ";
        }
		if($this->order_rm)
        {
            $ksqls[] = " act.biaoji = 'rm'";
        }
		if($this->order_tj)
        {
            $ksqls[] = " act.biaoji = 'tj'";
        }
		//end add
        $ksqls[] = " arc.arcrank > -1 ";
		$ksqls[] = " arc.ismake <> 0 ";
		if($this->tianshu)
        {
			if($this->tianshu == 1){
				$ksqls[] = " act.tianshu BETWEEN 1 AND 5";
			}elseif($this->tianshu == 2){
				$ksqls[] = " act.tianshu BETWEEN 6 AND 9";
			}elseif($this->tianshu == 3){
				$ksqls[] = " act.tianshu BETWEEN 10 AND 15";
			}elseif($this->tianshu == 4){
				$ksqls[] = " act.tianshu>15";
			}
        }
		if($this->jiage)
        {
			if($this->jiage == 1){
				$ksqls[] = " act.jiage<5001";
			}elseif($this->jiage == 2){
				$ksqls[] = " act.jiage BETWEEN 5001 AND 8000";
			}elseif($this->jiage == 3){
				$ksqls[] = " act.jiage BETWEEN 8001 AND 10000";
			}elseif($this->jiage == 4){
				$ksqls[] = " act.jiage BETWEEN 10001 AND 15000";
			}elseif($this->jiage == 5){
				$ksqls[] = " act.jiage>15000";
			}
        }
		
        $this->AddSql = ($ksql=='' ? join(' AND ',$ksqls) : join(' AND ',$ksqls)." AND (".$ksql.")" );
        if($this->ChannelType < 0 || $this->ChannelTypeid< 0){
            if($this->ChannelType=="0") $id=$this->ChannelTypeid;
            else $id=$this->ChannelType;
            $row = $this->dsql->GetOne("SELECT addtable FROM `#@__channeltype` WHERE id=$id");
            $addtable = trim($row['addtable']);
            $this->AddTable=$addtable;
        }else{
            $this->AddTable="#@__archives";
        }
        $cquery = "SELECT arc.*,act.* FROM `{$this->AddTable}` arc LEFT JOIN `cty_addon7` act ON arc.id=act.aid WHERE  arc.".$this->AddSql;//用于统计页码数
        //var_dump($cquery);
        $hascode = md5($cquery);//用于统计页码数
        $row = $this->dsql->GetOne("SELECT * FROM `#@__arccache` WHERE `md5hash`='".$hascode."' ");//用于统计页码数
        $uptime = time();
        if(is_array($row) && time()-$row['uptime'] < 3600 * 24)
        {
            $aids = explode(',', $row['cachedata']);
            $this->TotalResult = count($aids)-1;
            $this->RsFields = $row['cachedata'];
        }
        else
        {
            if($this->TotalResult==-1)
            {
                $this->dsql->SetQuery($cquery);
                $this->dsql->execute();
                $aidarr = array();
                $aidarr[] = 0;
                while($row = $this->dsql->getarray())
                {
                    if($this->ChannelType< 0 ||$this->ChannelTypeid< 0) $aidarr[] = $row['aid'];
                    else $aidarr[] = $row['id'];
                }
                $nums = count($aidarr)-1;
                $aids = implode(',', $aidarr);
                $delete = "DELETE FROM `#@__arccache` WHERE uptime<".(time() - 3600 * 24);
                $this->dsql->SetQuery($delete);
                $this->dsql->executenonequery();
                $insert = "INSERT INTO `#@__arccache` (`md5hash`, `uptime`, `cachedata`)
                 VALUES('$hascode', '$uptime', '$aids')";
                $this->dsql->SetQuery($insert);
                $this->dsql->executenonequery();
                $this->TotalResult = $nums;
            }
        }
    }

    /**
     *  显示列表
     *
     * @access    public
     * @param     string
     * @return    string
     */
    function Display()
    {
        foreach($this->dtp->CTags as $tagid=>$ctag)
        {
            $tagname = $ctag->GetName();
            if($tagname=="list")
            {
                $limitstart = ($this->PageNo-1) * $this->PageSize;
                $row = $this->PageSize;
                if(trim($ctag->GetInnerText())=="")
                {
                    $InnerText = GetSysTemplets("list_fulllist.htm");
                }
                else
                {
                    $InnerText = trim($ctag->GetInnerText());
                }
                $this->dtp->Assign($tagid,
                $this->GetArcList($limitstart,
                $row,
                $ctag->GetAtt("col"),
                $ctag->GetAtt("titlelen"),
                $ctag->GetAtt("infolen"),
                $ctag->GetAtt("imgwidth"),
                $ctag->GetAtt("imgheight"),
                $this->ChannelType,
                $this->OrderBy,
                $InnerText,
                $ctag->GetAtt("tablewidth"))
                );
            }
            else if($tagname=="pagelist")
            {
                $list_len = trim($ctag->GetAtt("listsize"));
                if($list_len=="")
                {
                    $list_len = 3;
                }
                $this->dtp->Assign($tagid,$this->GetPageListDM($list_len));
            }
			//add by ks
			else if($tagname=="pagelistlong")
            {
                $list_len = trim($ctag->GetAtt("listsize"));
                $ctag->GetAtt("listitem")=="" ? $listitem="index,pre,pageno,next,end,option" : $listitem=$ctag->GetAtt("listitem");
                if($list_len==""){
                    $list_len = 3;
                }
                $this->dtp->Assign($tagid,$this->GetPageListDMlong($list_len,$listitem));
            }
			else if($tagname=="ks_ziduan")
            {
                $ctag->GetAtt("listitem")=="" ? $listitem="chufachengshi" : $listitem=$ctag->GetAtt("listitem");
                $this->dtp->Assign($tagid,$this->ks_ziduan($listitem));
            }
			//end add
            else if($tagname=="likewords")
            {
                $this->dtp->Assign($tagid,$this->GetLikeWords($ctag->GetAtt('num')));
            }
            else if($tagname=="hotwords")
            {
                $this->dtp->Assign($tagid,lib_hotwords($ctag,$this));
            }
			else if($tagname=="arclist")
			{
			$this->dtp->Assign($tagid,lib_arclist($ctag,$this));
			}
			else if($tagname=="channelartlist")
			{
			$this->dtp->Assign($tagid,lib_channelartlist($ctag,$this));
			}
			else if($tagname=="myad")
			{
			$this->dtp->Assign($tagid,lib_myad($ctag,$this));
			} 
			else if($tagname=="php")
			{
			$this->dtp->Assign($tagid,lib_php($ctag,$this));
			}
			else if($tagname=="sql")
			{
			$this->dtp->Assign($tagid,lib_sql($ctag,$this));
			}
            else if($tagname=="field")
            {
                //类别的指定字段
                if(isset($this->Fields[$ctag->GetAtt('name')]))
                {
                    $this->dtp->Assign($tagid,$this->Fields[$ctag->GetAtt('name')]);
                }
                else
                {
                    $this->dtp->Assign($tagid,"");
                }
            }
            else if($tagname=="channel")
            {
                //下级频道列表
                if($this->TypeID>0)
                {
                    $typeid = $this->TypeID; $reid = $this->TypeLink->TypeInfos['reid'];
                }
                else
                {
                    $typeid = 0; $reid=0;
                }
                $GLOBALS['envs']['typeid'] = $typeid;
                $GLOBALS['envs']['reid'] = $typeid;
                $this->dtp->Assign($tagid,lib_channel($ctag,$this));
            }//End if

        }
        global $keyword,  $oldkeyword;
        if(!empty($oldkeyword)) $keyword = $oldkeyword;
        $this->dtp->Display();
    }

    /**
     *  获得文档列表
     *
     * @access    public
     * @param     int  $limitstart  限制开始  
     * @param     int  $row  行数 
     * @param     int  $col  列数
     * @param     int  $titlelen  标题长度
     * @param     int  $infolen  描述长度
     * @param     int  $imgwidth  图片宽度
     * @param     int  $imgheight  图片高度
     * @param     string  $achanneltype  列表类型
     * @param     string  $orderby  排列顺序
     * @param     string  $innertext  底层模板
     * @param     string  $tablewidth  表格宽度
     * @return    string
     */
    function GetArcList($limitstart=0,$row=10,$col=1,$titlelen=30,$infolen=250,
    $imgwidth=120,$imgheight=90,$achanneltype="all",$orderby="default",$innertext="",$tablewidth="100")
    {
        global $cfg_sphinx_article;
        $typeid=$this->TypeID;
        if($row=='') $row = 10;
        if($limitstart=='') $limitstart = 0;
        if($titlelen=='') $titlelen = 30;
        if($infolen=='') $infolen = 250;
        if($imgwidth=='') $imgwidth = 120;
        if($imgheight='') $imgheight = 120;
        if($achanneltype=='') $achanneltype = '0';
        $orderby = $orderby=='' ? 'default' : strtolower($orderby);
        $tablewidth = str_replace("%","",$tablewidth);
        if($tablewidth=='') $tablewidth=100;
        if($col=='') $col=1;
        $colWidth = ceil(100/$col);
        $tablewidth = $tablewidth."%";
        $colWidth = $colWidth."%";
        $innertext = trim($innertext);
        if($innertext=='')
        {
            $innertext = GetSysTemplets("search_list.htm");
        }
        
        if ($cfg_sphinx_article == 'Y')
        {
            $ordersql = '';
            if($this->ChannelType< 0 ||$this->ChannelTypeid< 0)
            {
                if($orderby=="id"){
                    $ordersql="@id desc";
                }else{
                    $ordersql="@senddate desc";
                }
            } else {
                if($orderby=="senddate")
                {
                    $ordersql="@senddate desc";
                }
                else if($orderby=="pubdate")
                {
                    $ordersql="@pubdate desc";
                }
                else if($orderby=="id")
                {
                    $ordersql="@id desc";
                }
                else
                {
                    $ordersql="@sortrank desc";
                }
            }
            
            $this->sphinx->SetLimits($limitstart, (int)$row, ($row>1000) ? $row : 1000);
            $res = array();
            $res = AutoCharset($this->sphinx->Query($this->Keywords, 'mysql, delta'), 'utf-8', 'gbk');
			
            foreach ($res['words'] as $k => $v) {
                $this->Keywords .= " $k";
            }
            foreach($res['matches'] as $_v) {
                $aids[] = $_v['id'];
            }
            
            $aids = @implode(',', $aids);
            
            //搜索
            $query = "SELECT arc.*,act.typedir,act.typename,act.isdefault,act.defaultname,act.namerule,
            act.namerule2,act.ispart,act.moresite,act.siteurl,act.sitepath
            FROM `#@__archives` arc LEFT JOIN `#@__arctype` act ON arc.typeid=act.id
            WHERE arc.id IN ($aids)";
            
        } else {
            //排序方式
            $ordersql = '';
            if($this->ChannelType< 0 ||$this->ChannelTypeid< 0)
            {
                if($orderby=="id"){
                    $ordersql="ORDER BY arc.aid desc";
                }else{
                    $ordersql="ORDER BY arc.senddate desc";
                }
            } else {
                if($orderby=="senddate")
                {
                    $ordersql=" ORDER BY arc.senddate desc";
                }
                else if($orderby=="pubdate")
                {
                    $ordersql=" ORDER BY arc.pubdate desc";
                }
                else if($orderby=="id")
                {
                    $ordersql="  ORDER BY arc.id desc";
                }
                else
                {
                    $ordersql=" ORDER BY arc.sortrank desc";
                }
            }
			
			//add by ks
            if($this->ChannelType = 7){
				if($this->order_jiage && $this->order_jiage == 1){
					$ordersql=" ORDER BY act.jiage asc";
				}
				elseif($this->order_jiage && $this->order_jiage == 2){
					$ordersql=" ORDER BY act.jiage desc";
				}
				if($this->order_tianshu && $this->order_tianshu == 1){
					$ordersql=" ORDER BY act.tianshu asc";
				}
				elseif($this->order_tianshu && $this->order_tianshu == 2){
					$ordersql=" ORDER BY act.tianshu desc";
				}
			}
			
			//搜索
			if($this->ChannelType = 7){
				$query = "SELECT arc.*,act.*,ack.typedir,ack.typename,ack.isdefault,ack.defaultname,ack.namerule,
            ack.namerule2,ack.ispart,ack.moresite,ack.siteurl,ack.sitepath
            FROM `{$this->AddTable}` arc LEFT JOIN `cty_addon7` act ON arc.id=act.aid LEFT JOIN `#@__arctype` ack ON arc.typeid=ack.id
            WHERE arc.{$this->AddSql} $ordersql LIMIT $limitstart,$row";
			}else{
				$query = "SELECT arc.*,act.typedir,act.typename,act.isdefault,act.defaultname,act.namerule,
            act.namerule2,act.ispart,act.moresite,act.siteurl,act.sitepath
            FROM `{$this->AddTable}` arc LEFT JOIN `#@__arctype` act ON arc.typeid=act.id
            WHERE {$this->AddSql} $ordersql LIMIT $limitstart,$row";
			}
			//end add
			
			//edit by ks
			/*$query = "SELECT arc.*,act.typedir,act.typename,act.isdefault,act.defaultname,act.namerule,
            act.namerule2,act.ispart,act.moresite,act.siteurl,act.sitepath
            FROM `{$this->AddTable}` arc LEFT JOIN `#@__arctype` act ON arc.typeid=act.id
            WHERE {$this->AddSql} $ordersql LIMIT $limitstart,$row";*/
			//end edit
        }
		
		//var_dump($this->AddSql);
		$cty_num = $this->dsql->ExecuteNoneQuery2($query);
        $this->dsql->SetQuery($query);
        $this->dsql->Execute("al");
		//var_dump($this->AddSql);
		//var_dump($ordersql);
        $artlist = "";
        if($col>1)
        {
            $artlist = "<table width='$tablewidth' border='0' cellspacing='0' cellpadding='0'>\r\n";
        }
        $this->dtp2->LoadSource($innertext);
        for($i=0;$i<$row;$i++)
        {
            if($col>1)
            {
                $artlist .= "<tr>\r\n";
            }
            for($j=0;$j<$col;$j++)
            {
                if($col>1)
                {
                    $artlist .= "<td width='$colWidth'>\r\n";
                }
                if($row = $this->dsql->GetArray("al"))
                {
                    if($this->ChannelType< 0 || $this->ChannelTypeid< 0) {
                        $row["id"]=$row["aid"];
                        $row["ismake"]=empty($row["ismake"])? "" : $row["ismake"];
                        $row["filename"]=empty($row["filename"])? "" : $row["filename"];
                        $row["money"]=empty($row["money"])? "" : $row["money"];
                        $row["description"]=empty($row["description "])? "" : $row["description"];
                        $row["pubdate"]=empty($row["pubdate  "])? $row["senddate"] : $row["pubdate"];
                    }
                    //处理一些特殊字段
					$row["namerule"] = 
                    $row["arcurl"] = GetFileUrl($row["id"],$row["typeid"],$row["senddate"],$row["title"],
                    $row["ismake"],$row["arcrank"],$row["namerule"],$row["typedir"],$row["money"],$row['filename'],$row["moresite"],$row["siteurl"],$row["sitepath"]);
                    $row["description"] = $this->GetRedKeyWord(cn_substr($row["description"],$infolen));
                    $row["title"] = $this->GetRedKeyWord(cn_substr($row["title"],$titlelen));
                    $row["id"] =  $row["id"];
                    if($row['litpic'] == '-' || $row['litpic'] == '')
                    {
                        $row['litpic'] = $GLOBALS['cfg_cmspath'].'/images/defaultpic.gif';
                    }
                    if(!preg_match("/^http:\/\//", $row['litpic']) && $GLOBALS['cfg_multi_site'] == 'Y')
                    {
                        $row['litpic'] = $GLOBALS['cfg_mainsite'].$row['litpic'];
                    }
                    $row['picname'] = $row['litpic'];
                    $row["typeurl"] = GetTypeUrl($row["typeid"],$row["typedir"],$row["isdefault"],$row["defaultname"],$row["ispart"],$row["namerule2"],$row["moresite"],$row["siteurl"],$row["sitepath"]);
                    $row["infos"] = $row["description"];
                    $row["filename"] = $row["arcurl"];
                    $row["stime"] = GetDateMK($row["pubdate"]);
                    $row["textlink"] = "<a href='".$row["filename"]."'>".$row["title"]."</a>";
                    $row["typelink"] = "[<a href='".$row["typeurl"]."'>".$row["typename"]."</a>]";
                    $row["imglink"] = "<a href='".$row["filename"]."'><img src='".$row["picname"]."' border='0' width='$imgwidth' height='$imgheight'></a>";
                    $row["image"] = "<img src='".$row["picname"]."' border='0' width='$imgwidth' height='$imgheight'>";
                    $row['plusurl'] = $row['phpurl'] = $GLOBALS['cfg_phpurl'];
                    $row['memberurl'] = $GLOBALS['cfg_memberurl'];
                    $row['templeturl'] = $GLOBALS['cfg_templeturl'];
                    if(is_array($this->dtp2->CTags))
                    {
                        foreach($this->dtp2->CTags as $k=>$ctag)
                        {
                            if($ctag->GetName()=='array')
                            {
                                //传递整个数组，在runphp模式中有特殊作用
                                $this->dtp2->Assign($k,$row);
                            }
                            else
                            {
                                if(isset($row[$ctag->GetName()]))
                                {
                                    $this->dtp2->Assign($k,$row[$ctag->GetName()]);
                                }
                                else
                                {
                                    $this->dtp2->Assign($k,'');
                                }
                            }
                        }
                    }
                    $artlist .= $this->dtp2->GetResult();
                }//if hasRow

                else
                {
                    $artlist .= "";
                }
                if($col>1) $artlist .= "</td>\r\n";
            }//Loop Col

            if($col>1)
            {
                $artlist .= "</tr>\r\n";
            }
        }//Loop Line

        if($col>1)
        {
            $artlist .= "</table>\r\n";
        }
        $this->dsql->FreeResult("al");

        return $artlist;
    }

    /**
     *  获取动态的分页列表
     *
     * @access    public
     * @param     string  $list_len  列表宽度
     * @return    string
     */
    function GetPageListDM($list_len)
    {
        global $oldkeyword;
        $prepage="";
        $nextpage="";
        $prepagenum = $this->PageNo - 1;
        $nextpagenum = $this->PageNo + 1;
        if($list_len=="" || preg_match("/[^0-9]/", $list_len))
        {
            $list_len=3;
        }
        $totalpage = ceil($this->TotalResult / $this->PageSize);
        if($totalpage<=1 && $this->TotalResult>0)
        {
            return "共1页/".$this->TotalResult."条记录";
        }
        if($this->TotalResult == 0)
        {
            return "共0页/".$this->TotalResult."条记录";
        }
        $purl = $this->GetCurUrl();
        
        $oldkeyword = (empty($oldkeyword) ? $this->Keyword : $oldkeyword);

        //当结果超过限制时，重设结果页数
        if($this->TotalResult > $this->SearchMaxRc)
        {
            $totalpage = ceil($this->SearchMaxRc/$this->PageSize);
        }
        $infos = "<td>共找到<b>".$this->TotalResult."</b>条记录/最大显示<b>{$totalpage}</b>页 </td>\r\n";
        $geturl = "keyword=".urlencode($oldkeyword)."&searchtype=".$this->SearchType;
        $hidenform = "<input type='hidden' name='keyword' value='".rawurldecode($oldkeyword)."'>\r\n";
        $geturl .= "&channeltype=".$this->ChannelType."&orderby=".$this->OrderBy;
        $hidenform .= "<input type='hidden' name='channeltype' value='".$this->ChannelType."'>\r\n";
        $hidenform .= "<input type='hidden' name='orderby' value='".$this->OrderBy."'>\r\n";
        $geturl .= "&kwtype=".$this->KType."&pagesize=".$this->PageSize;
        $hidenform .= "<input type='hidden' name='kwtype' value='".$this->KType."'>\r\n";
        $hidenform .= "<input type='hidden' name='pagesize' value='".$this->PageSize."'>\r\n";
        $geturl .= "&typeid=".$this->TypeID."&TotalResult=".$this->TotalResult."&";
        $hidenform .= "<input type='hidden' name='typeid' value='".$this->TypeID."'>\r\n";
        $hidenform .= "<input type='hidden' name='TotalResult' value='".$this->TotalResult."'>\r\n";
        $purl .= "?".$geturl;

        //获得上一页和下一页的链接
        if($this->PageNo != 1)
        {
            $prepage.="<td width='50'><a href='".$purl."PageNo=$prepagenum'>上一页</a></td>\r\n";
            $indexpage="<td width='30'><a href='".$purl."PageNo=1'>首页</a></td>\r\n";
        }
        else
        {
            $indexpage="<td width='30'>首页</td>\r\n";
        }
        if($this->PageNo!=$totalpage && $totalpage>1)
        {
            $nextpage.="<td width='50'><a href='".$purl."PageNo=$nextpagenum'>下一页</a></td>\r\n";
            $endpage="<td width='30'><a href='".$purl."PageNo=$totalpage'>末页</a></td>\r\n";
        }
        else
        {
            $endpage="<td width='30'>末页</td>\r\n";
        }

        //获得数字链接
        $listdd="";
        $total_list = $list_len * 2 + 1;
        if($this->PageNo >= $total_list)
        {
            $j = $this->PageNo - $list_len;
            $total_list = $this->PageNo + $list_len;
            if($total_list > $totalpage)
            {
                $total_list = $totalpage;
            }
        }
        else
        {
            $j=1;
            if($total_list > $totalpage)
            {
                $total_list = $totalpage;
            }
        }
        for($j; $j<=$total_list; $j++)
        {
            if($j == $this->PageNo)
            {
                $listdd.= "<td>$j&nbsp;</td>\r\n";
            }
            else
            {
                $listdd.="<td><a href='".$purl."PageNo=$j'>[".$j."]</a>&nbsp;</td>\r\n";
            }
        }
        $plist  =  "<table border='0' cellpadding='0' cellspacing='0'>\r\n";
        $plist .= "<tr align='center' style='font-size:10pt'>\r\n";
        $plist .= "<form name='pagelist' action='".$this->GetCurUrl()."'>$hidenform";
        $plist .= $infos;
        $plist .= $indexpage;
        $plist .= $prepage;
        $plist .= $listdd;
        $plist .= $nextpage;
        $plist .= $endpage;
        if($totalpage>$total_list)
        {
            $plist.="<td width='38'><input type='text' name='PageNo' style='width:28px;height:14px' value='".$this->PageNo."' /></td>\r\n";
            $plist.="<td width='30'><input type='submit' name='plistgo' value='GO' style='width:30px;height:22px;font-size:9pt' /></td>\r\n";
        }
        $plist .= "</form>\r\n</tr>\r\n</table>\r\n";
        return $plist;
    }
	
	//add by ks
    function GetPageListDMlong($list_len,$listitem="index,end,pre,next,pageno")
    {
        global $cfg_rewrite;
        $prepage = $nextpage = '';
        $prepagenum = $this->PageNo-1;
        $nextpagenum = $this->PageNo+1;
        if($list_len=='' || preg_match("/[^0-9]/", $list_len))
        {
            $list_len=3;
        }
        $totalpage = ceil($this->TotalResult/$this->PageSize);
        /*if($totalpage<=1 && $this->TotalResult>0)
        {
            return "<li><span class=\"pageinfo\">共 1 页/".$this->TotalResult." 条记录</span></li>\r\n";
        }
        if($this->TotalResult == 0)
        {
            return "<li><span class=\"pageinfo\">共 0 页/".$this->TotalResult." 条记录</span></li>\r\n";
        }*/
        //$maininfo = "<li><span class=\"pageinfo\">共 <strong>{$totalpage}</strong>页<strong>".$this->TotalResult."</strong>条</span></li>\r\n";
		if($totalpage == 0){
			$maininfo = "<b>".$this->PageNo."/1</b>";
		}else{
			$maininfo = "<b>".$this->PageNo."/{$totalpage}</b>";
		}
		
        $purl = $this->GetCurUrl();
        // 如果开启为静态,则对规则进行替换
        /*if($cfg_rewrite == 'Y')
        {
            $nowurls = preg_replace("/\-/", ".php?", $purl);
            $nowurls = explode("?", $nowurls);
            $purl = $nowurls[0];
        }*/
        
        $oldkeyword = (empty($oldkeyword) ? $this->Keyword : $oldkeyword);

        //$geturl = "tid=".$this->TypeID."&TotalResult=".$this->TotalResult."&";
		//$purl .= '?'.$geturl;
        
		//$infos = "<td>共找到<b>".$this->TotalResult."</b>条记录/最大显示<b>{$totalpage}</b>页 </td>\r\n";
        $geturl = "q=".urlencode($oldkeyword)."&searchtype=".$this->SearchType;
        $hidenform = "<input type='hidden' name='keyword' value='".rawurldecode($oldkeyword)."'>\r\n";
        $geturl .= "&channeltype=".$this->ChannelType;
        $hidenform .= "<input type='hidden' name='channeltype' value='".$this->ChannelType."'>\r\n";
        //$hidenform .= "<input type='hidden' name='orderby' value='".$this->OrderBy."'>\r\n";
        $geturl .= "&kwtype=".$this->KType;
        $hidenform .= "<input type='hidden' name='kwtype' value='".$this->KType."'>\r\n";
        //$hidenform .= "<input type='hidden' name='pagesize' value='".$this->PageSize."'>\r\n";
        $geturl .= "&xianlu=".$this->xianlu."&mudidi=".$this->mudidi;
        $hidenform .= "<input type='hidden' name='xianlu' value='".$this->xianlu."'>\r\n";
        $hidenform .= "<input type='hidden' name='mudidi' value='".$this->mudidi."'>\r\n";
        $purl .= "?".$geturl;
		$optionlist = '';

		//获得上一页和下一页的链接
        if($this->PageNo != 1)
        {
            $prepage.="<li><a href='".$purl."&PageNo=$prepagenum'>上一页</a></li>\r\n";
            $indexpage="<li><a href='".$purl."&PageNo=1'>首页</a></li>\r\n";
			//$nextpage.="<li><a href='javascript:void(0);'>下一页</a></li>\r\n";
        }
        else
        {
            $indexpage="<li><a>首页</a></li>\r\n";
        }
        if($this->PageNo!=$totalpage && $totalpage>1)
        {
			//$prepage.="<li><a href='javascript:void(0);'>上一页</a></li>\r\n";
            $nextpage.="<li><a href='".$purl."&PageNo=$nextpagenum'>下一页</a></li>\r\n";
            $endpage="<li><a href='".$purl."&PageNo=$totalpage'>末页</a></li>\r\n";
        }
        else
        {
            $endpage="<li><a>末页</a></li>\r\n";
        }
		
		//获得数字链接
        $listdd="";
        $total_list = $list_len * 2 + 1;
        if($this->PageNo >= $total_list)
        {
            $j = $this->PageNo-$list_len;
            $total_list = $this->PageNo+$list_len;
            if($total_list>$totalpage)
            {
                $total_list=$totalpage;
            }
        }
        else
        {
            $j=1;
            if($total_list>$totalpage)
            {
                $total_list=$totalpage;
            }
        }
        for($j;$j<=$total_list;$j++)
        {
            if($j==$this->PageNo)
            {
                $listdd.= "<li class=\"thisclass\">$j</li>\r\n";
            }
            else
            {
                $listdd.="<li><a href='".$purl."&PageNo=$j'>".$j."</a></li>\r\n";
            }
        }
		
		$plist = '';
        //$hidenform = "<input type='hidden' name='tid' value='".$this->TypeID."'>\r\n";
        //$hidenform .= "<input type='hidden' name='TotalResult' value='".$this->TotalResult."'>\r\n";

        
        if(preg_match('/index/i', $listitem)) $plist .= $indexpage;
        if(preg_match('/pre/i', $listitem)) $plist .= $prepage;
        if(preg_match('/pageno/i', $listitem)) $plist .= $listdd;
        if(preg_match('/next/i', $listitem)) $plist .= $nextpage;
        if(preg_match('/end/i', $listitem)) $plist .= $endpage;
        if(preg_match('/option/i', $listitem)) $plist .= $optionlist;
        if(preg_match('/info/i', $listitem)) $plist .= $maininfo;
		$plist = "<form name='pagelist' action='".$this->GetCurUrl()."'>$hidenform".$plist."</form>\r\n</tr>\r\n</table>\r\n";
        
        return $plist;
    }
	
	function ks_ziduan($listitem="chufachengshi"){
		global $cfg_rewrite;
		if($this->chufadi){
			$chufachengshi = $this->chufadi;
		}else{
			$chufachengshi = "大连";
		}
		$plist = '';
		if(preg_match('/chufachengshi/i', $listitem)) $plist = $chufachengshi;
		//$chufachengshi = $this->dsql->GetOne("SELECT * FROM `cty_addon7` WHERE chufachengshi='".$this->chufadi."'");
		return $plist;
	}
	
	function ks_return(){
		$cty_query = "SELECT arc.*
            FROM `{$this->AddTable}` arc LEFT JOIN `cty_addon7` act ON arc.id=act.aid
            WHERE arc.typeid IN (".GetSonIds("25").",".GetSonIds("26").") AND arc.channel='7' AND act.chufachengshi='".$this->chufadi."' AND arc.arcrank > -1 AND arc.ismake <> 0";
		$cty_num = $this->dsql->ExecuteNoneQuery2($cty_query);
		$this->cty_num = $cty_num;
		
		$zyx_query = "SELECT arc.*
            FROM `{$this->AddTable}` arc LEFT JOIN `cty_addon7` act ON arc.id=act.aid
            WHERE arc.typeid IN (70,72) AND arc.channel='7' AND act.chufachengshi='".$this->chufadi."' AND arc.arcrank > -1 AND arc.ismake <> 0";
		$zyx_num = $this->dsql->ExecuteNoneQuery2($zyx_query);
		$this->zyx_num = $zyx_num;
		
	}
	
	function tianshu_num($num){
		/*if($this->xianlu == "25,26,18"){
			$ksqls[] = " typeid IN (".GetSonIds("25").",".GetSonIds("26").",".GetSonIds("18").") ";
		}elseif($this->xianlu == "25,26"){
			$ksqls[] = " typeid IN (".GetSonIds("25").",".GetSonIds("26").") ";
		}else{
			$ksqls[] = " typeid IN (".GetSonIds($this->xianlu).") ";
		}*/
		$tianshu_query = "SELECT arc.*,act.*
            FROM `{$this->AddTable}` arc LEFT JOIN `cty_addon7` act ON arc.id=act.aid
            WHERE arc.typeid IN (".GetSonIds("25").",".GetSonIds("26").",".GetSonIds("18").") AND arc.channel='7' AND act.chufachengshi='".$this->chufadi."' AND arc.arcrank > -1 AND arc.ismake <> 0 AND act.tianshu ".$num;
			//var_dump($tianshu_query);
		$tianshu_num = $this->dsql->ExecuteNoneQuery2($tianshu_query);
		echo $tianshu_num;
	}
	
	function jiage_num($num){
		$jiage_query = "SELECT arc.*,act.*
            FROM `{$this->AddTable}` arc LEFT JOIN `cty_addon7` act ON arc.id=act.aid
            WHERE arc.typeid IN (".GetSonIds("25").",".GetSonIds("26").",".GetSonIds("18").") AND arc.channel='7' AND act.chufachengshi='".$this->chufadi."' AND arc.arcrank > -1 AND arc.ismake <> 0 AND act.jiage ".$num;
		$jiage_num = $this->dsql->ExecuteNoneQuery2($jiage_query);
		echo $jiage_num;
	}
	//end add

    /**
     *  获得当前的页面文件的url
     *
     * @access    public
     * @return    string
     */
    function GetCurUrl()
    {
        if(!empty($_SERVER["REQUEST_URI"]))
        {
            $nowurl = $_SERVER["REQUEST_URI"];
            $nowurls = explode("?",$nowurl);
            $nowurl = $nowurls[0];
        }
        else
        {
            $nowurl = $_SERVER["PHP_SELF"];
        }
        return $nowurl;
    }
	
}//End Class