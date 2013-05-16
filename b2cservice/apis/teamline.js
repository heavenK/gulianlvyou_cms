/*
* 用于参团游详情页出团日历
*
*/

var arrgroup;
var arrlinegroup = new Array();
var nowgroup = null;
var arrline = null;
function loadgroup(lineid) {
	url = SERVER_GET_XIANLU + '/erpxianluID/' + 24;
	//url = SERVER_GET_XIANLU + '/erpxianluID/' + _lineid;
	jQuery.getJSON(url +"&jsoncallback=?", function(data){  
        if (data.error != true) {
            arrlinegroup = arrgroup = data.data;
			nowgroup = arrlinegroup[0];
            arrline = data.line;
            initcalendar();
        }
        else {
			if(data.msg)
				alert(data.msg+"，系统将自动跳转到首页!");
			else
				alert("该线路名额已满或已过报名截止日期，系统将自动跳转到首页!"); 
			window.location.href = ROOT_URL;
        }
    });
}

loadgroup(_lineid);

function getgroupbydate(group, date) {
    var ret = null;
    for (var i = 0; i < group.length; i++) {
        if (group[i].date == date) {
            if (ret == null || group[i].adult_price < ret.adult_price) ret = group[i];
        }
    }
    if (ret != null && ret.renshu == 0) {
        for (var i = 0; i < group.length; i++) {
            if (group[i].date == date) {
                if (group[i].renshu > 0) {
                    ret = group[i]; break;
                }
            }
        }
    }
    return ret;
}

function getgroupbyid(group, id) {
    for (var i = 0; i < group.length; i++) {
        if (group[i].chanpinID == id) {
            return group[i];
        }
    }
    return null;
}

//初始化弹出预订窗口信息
function initFloatDiv() {
    if (arrgroup == null || arrgroup.length == 0) { alert("该线路暂无出团，请选择其它产品"); return; }
	alert(arrlinegroup[0].chanpinID)
	alert(nowgroup.chanpinID)
    var tmp = "";
    $("#Popupbox select").empty();
    for (var i = 0; i < arrlinegroup.length; i++) {
        tmp = "<option value='" + arrlinegroup[i].chanpinID + "'";
        if (nowgroup.chanpinID == arrlinegroup[i].chanpinID) {
            tmp += " selected='true'";
        }
        tmp += ">" + arrlinegroup[i].date + "出发　零售价：￥" + arrlinegroup[i].adult_price;
        $("#Popupbox select").append(tmp);
    }
	alert(tmp)
    initFloatPerson(nowgroup);
    $("#Popupbox select").bind("change", function() { initFloatPerson(arrlinegroup[$("#Popupbox select").get(0).selectedIndex]); });

    if (nowgroup.child_price == 0) {
        $("#Popupbox ul li:last-child").remove();
        $(".stastics span").remove();
    }

}

function initFloatPerson(group) {
    $(".pro_table td:first").html(group.date);
    $(".pro_table td:eq(1)").html(group.enddate);
    $(".pro_table td:last").html((group.renshu > 9 ? ">9" : (group.renshu <= 0 ? 0 : group.renshu)));
    $(".tuanhao").remove();
    $("<div class='tuanhao'>团队编号：" + group.erpno + "</div>").insertBefore(".pro_table");

    //删除原有的   添加新版
    $("#Popupbox ul").remove();
    var text = "";
	text += '<ul><li class="pwidth">标准零售价：</li><li class="pwidth2"><b class="piaohong">￥' + group.adult_price + '</b></li>'
		+ '<li> <span>成人：</span> <span class="add_count"><input type="text" value="1"  /><span class="less"></span> <span class="add"></span> </span> <span>&nbsp;人</span> </li>'
		+ '<li> <span>儿童：</span> <span class="add_count"><input type="text" value="0"  /><span class="less"></span> <span class="add"></span> </span> <span>&nbsp;人</span> </li></ul>';
    $(text).insertAfter(".pro_table");
    $("#Popupbox ul input").bind("keyup", function() { this.value = this.value.replace(/\D/g, ''); if (this.value == "") this.value = "0"; computeprice(this, 1); });
    $("#Popupbox ul input").bind("afterpaste", function() { this.value = this.value.replace(/\D/g, ''); if (this.value == "") this.value = "0"; computeprice(this, 1); });
    $("#Popupbox ul span .add").bind("click", function() { var inp = $(this).parent().find("input").eq(0); inp.val(parseInt(inp.val()) + 1); computeprice(this, 2); });
    $("#Popupbox ul span .less").bind("click", function() { var inp = $(this).parent().find("input").eq(0); if (parseInt(inp.val()) <= 0) inp.val(0); else inp.val(parseInt(inp.val()) - 1); computeprice(this, 2); });

    //设置报名截止日信息
    var now = new Date();
    var arrenddate = group.enddate.split('-');
    if (arrenddate[0] == now.getFullYear() && parseInt(arrenddate[1]) == now.getMonth() + 1 && parseInt(arrenddate[2]) == now.getDate() && now.getHours() > 10) {//当天10点前
        $(".pro_table th:eq(1)").html("<em class='getred_bg'>报名资料截止日期</em><div>"
                + "<b>对不起，你已经错过最佳报名时间，请选择其他出发日期团组，如您持有自备签证，可选择继续报名。详情请咨询010-85906636。</b>"
                + "</div>");

    } else {
        $(".pro_table th:eq(1)").html("<em>报名资料截止日期</em><div>"
                + "<b>为确保你能顺利预订，请务必与截止日当日上午10点之前报名，并提交全部签证所需材料，否则将会影响到您正常出行，谢谢！</b>"
                + "<p>如有特殊情况未能在截止日当日上午10点之前递交全部签证资料的，请及时与您的旅游顾问联系。</p>"
                + "</div>");
    }
    $(".pro_table th em").hover(function() {
        $(".pro_table th div").show();
    }, function() {
        $(".pro_table th div").hide();
    })
    $(".pro_table th div").hover(function() { $(this).show(); }, function() { $(this).hide(); })

    computeprice();
}


/*初始化日历*/
var calen = $("#calendar");
var nowYear, nowMonth, nowDay;
var arrDay = new Array(); // 存贮每一天
function initcalendar() {
    //日历控件当前 年 月  选择日期
    nowYear = parseInt(nowgroup.date.split('-')[0]);
    if (nowgroup.date.split('-')[1].indexOf("0") == 0) nowMonth = parseInt(nowgroup.date.split('-')[1].substr(1));
    else nowMonth = parseInt(nowgroup.date.split('-')[1]);

    $(".tab ul").html('');
    for (var i = 0; i < arrgroup.length; i++) {
        if ($(".tab ul").html().indexOf(">" + getTabTitle(arrgroup[i].date) + "<") == -1) {
            if (nowgroup.date.indexOf(getTabTitle(arrgroup[i].date)) == 0)
                $(".tab ul").append("<li class='curr'>" + getTabTitle(arrgroup[i].date) + "</li>");
            else
                $(".tab ul").append("<li>" + getTabTitle(arrgroup[i].date) + "</li>");
        }
    }
    // $(".tab ul li:first").attr('class', "curr");
    $(".tab ul li").bind("click", function() {
        nowYear = parseInt($(this).html().split('-')[0]);
        if ($(this).html().split('-')[1].indexOf("0") == 0) nowMonth = parseInt($(this).html().split('-')[1].substr(1));
        else nowMonth = parseInt($(this).html().split('-')[1]);
        adjustCalendar();

        $(".tab ul li").attr('class', "");
        $(this).attr("class", "curr");
    });

    adjustCalendar();
}

//生成日历控件body
function adjustCalendar() {
    //赋值
    for (var i = 0; i < 42; i++) arrDay[i] = "";
    for (var i = 0; i < new Date(nowYear, nowMonth, 0).getDate(); i++) arrDay[i + new Date(nowYear, nowMonth - 1, 1).getDay()] = i + 1;

    calen.find("tr[use=day]").remove();

    //生成当月日历
    var strbody = "";
    for (var i = 0; i < 6; i++) {
        strbody += "<tr use='day'>\n";
        for (var j = 0; j < 7; j++) {
            groupday = null;
            if (arrDay[i * 7 + j] != "") {
                groupday = getgroupbydate(arrlinegroup, getFullDate(nowYear, nowMonth, arrDay[i * 7 + j]));
                if (groupday == null) groupday = getgroupbydate(arrgroup, getFullDate(nowYear, nowMonth, arrDay[i * 7 + j]));
                else if (groupday.date == nowgroup.date) groupday = nowgroup;
            }
            if (groupday == null)
                strbody += "<td>" + arrDay[i * 7 + j] + "</td>\n";
            else if (groupday.renshu <= 0) //已满
                strbody += "<td class='has_pro'>" + arrDay[i * 7 + j] + "<span class='pro_state'>已满</span><span class='detail_data'></span><span class='pro_price'><em class='f5'>￥</em>" + groupday.adult_price + "</span></td>\n";
            else {
				strbody += "<td class='" + (groupday == nowgroup ? "current " : "") + "has_pro' use='group'>" + arrDay[i * 7 + j] + "<span class='pro_state'>剩余</span><span class='detail_data'>" + (groupday.renshu > 9 ? ">9" : groupday.renshu) + "</span><span class='pro_price'><em class='f5'>￥</em>" + groupday.adult_price + "</span></td>\n";
            	//默认所选日期
				if(groupday == nowgroup){
					$(".select_03 i").html("出发日期：" + nowgroup.date + "&nbsp;团队编号：" + nowgroup.erpno);
					$("a[rel='print']").attr("href", _lineid + "_trip.html?d=" + nowgroup.date + "&p=" + nowgroup.adult_price + "&g=" + nowgroup.erpno);
				}
			}
        }
        strbody += "</tr>\n";
    }
    calen.append(strbody);
    calen.find("td[use=tip]").attr("title", "该日期行程与当前页行程有差异，\r\n请点击日期查看具体行程");
    $("#calendar tr:gt(0)").find("td:nth-child(1)").addClass("bg01");
    $("#calendar tr:gt(0)").find("td:nth-child(2)").addClass("bg02");
    $("#calendar tr:gt(0)").find("td:nth-child(3)").addClass("bg02");
    $("#calendar tr:gt(0)").find("td:nth-child(4)").addClass("bg02");
    $("#calendar tr:gt(0)").find("td:nth-child(5)").addClass("bg02");
    $("#calendar tr:gt(0)").find("td:nth-child(6)").addClass("bg02");
    $("#calendar tr:gt(0)").find("td:nth-child(7)").addClass("bg01");

    //设置可点击 报名未满的单元格
    calen.find('td[use=group]').bind('click', function(evt) {
//        calen.find('td').each(function(i) {
//            $(this).removeClass("current");
//        });
//        $(this).addClass("current");
//        nowgroup = getgroupbydate(arrgroup, getFullDate(nowYear, nowMonth, parseInt($(this).text())));
//        $(".select_03 i").html("出发日期：" + nowgroup.date + "&nbsp;团队编号：" + nowgroup.erpno);
//        $("a[rel='print']").attr("href", _lineid + "_trip.html?d=" + nowgroup.date + "&p=" + nowgroup.adult_price + "&g=" + nowgroup.erpno);
		//点击预订
		doselectyuding();
    });
}

function getFullDate(year, month, day) {
    var ret = "";
    ret += year;
    ret += "-";
    if (month < 10) ret += "0";
    ret += month;
    ret += "-";
    if (day < 10) ret += "0";
    ret += day;
    return ret;
}

function getTabTitle(date) {
    var arr = date.split('-');
    return arr[0] + "-" + arr[1];
}

var css_width = 0;
var css_height = 0;
$(function() {
    var pagesize = getPageSize();
    var pageWidth = pagesize[0];
    var pageHeight = pagesize[1];
    var ScreenWidth = pagesize[2];
    var ScreenHeight = pagesize[3];
    css_width = (ScreenWidth - 600) / 2;
    css_height = (ScreenHeight - 350) / 2;
    //$("#Popupbox_bg").css({ "width": pageWidth, "height": pageHeight, "opacity": "0.8" });
    $(".book_03").click(function() {
		doselectyuding();
    })
})

function doselectyuding(){
//	if (document.documentElement && document.documentElement.scrollTop) {
//		var scorll = document.documentElement.scrollTop;
//	} else if (document.body) {
//		var scorll = document.body.scrollTop;
//	}
//	var realheight = scorll + css_height;
//	$("#Popupbox").css({ "top": realheight, "left": css_width });

	showyudingdiv();
}



function showyudingdiv() {
    initFloatDiv();

    $("#Popupbox").fadeIn("fast", function() {
//        $("#Popupbox_bg").fadeIn("fast");
        $("body").attr("overflow", "hidden");
    })

    $("#Popupbox .shutdown").click(function() {
        $("#Popupbox").fadeOut("fast", function() {
//            $("#Popupbox_bg").fadeOut("fast");
            $("body").attr("overflow", "auto");
        })
    })   
}

var totalnum = 0;
var clickurl = "";
var oldAgreevisa = false;
function computeprice(ctrl, type) {
alert($("#Popupbox select").val())
    var group = getgroupbyid(arrlinegroup, $("#Popupbox select").val());
    var pernum = 0;
    var childnum = 0;
    var pernumVisa = 0;
    var childnumVisa = 0;

	pernum = parseInt($("#Popupbox ul:first input:first").val());
	if (group.child_price > 0) {
		childnum = parseInt($("#Popupbox ul:first input:last").val());
	}
	if (pernum == 0) {
		$("#Popupbox ul input:first").val(1);
		pernum = 1;
	}
    var text = "";
        text = '<div class="stastics">你选择了：标准零售价成人<em>' + pernum + '</em>，儿童<em>' + childnum + '</em>，合计：<em class="f1">￥</em><em class="f2">' + (group.adult_price * pernum + group.child_price * childnum) + '</em></div>';
    
	$(".stastics").remove();
    $(".heji").remove();
    $(".youqingtips").remove();
    $(text).insertAfter("#Popupbox ul:last");

    totalnum = pernum + childnum;
	clickurl = BOOK_1_URL + "/chanpinID/"+group.chanpinID+"/chengrenshu/"+pernum+"/ertongshu/"+childnum;
	
    if (totalnum > group.renshu || group.renshu == 0 || pernum == 0) {
        $("#Popupbox .nextstep input").attr("src", "/b2cservice/apis/xiayibu_gray.gif");
        $("#Popupbox .nextstep input").unbind().bind("click", function() { alert("请检查预订人数，不可超过该团的剩余名额,并且成人数不能为0！"); });
    }
    else {
        $("#Popupbox .nextstep input").attr("src", "/b2cservice/apis/nextstep.gif");
        $("#Popupbox .nextstep input").unbind().bind("click", function() {
            $("#Popupbox").fadeOut("fast", function() {
//                $("#Popupbox_bg").fadeOut("fast");
                $("body").attr("overflow", "auto");
            })
            judge("judegeover('" + clickurl + "')");
        });
    }
}

function judegeover(url) {
    window.location.href = url;
}

function getPageSize() {
    var xScroll, yScroll;
    if (window.innerHeight && window.scrollMaxY) {
        xScroll = window.innerWidth + window.scrollMaxX;
        yScroll = window.innerHeight + window.scrollMaxY;
    } else if (document.body.scrollHeight > document.body.offsetHeight) {
        xScroll = document.body.scrollWidth;
        yScroll = document.body.scrollHeight;
    } else {
        xScroll = document.body.offsetWidth;
        yScroll = document.body.offsetHeight;
    }
    var windowWidth, windowHeight;
    if (self.innerHeight) {
        if (document.documentElement.clientWidth) {
            windowWidth = document.documentElement.clientWidth;
        } else {
            windowWidth = self.innerWidth;
        }
        windowHeight = self.innerHeight;
    } else if (document.documentElement && document.documentElement.clientHeight) {
        windowWidth = document.documentElement.clientWidth;
        windowHeight = document.documentElement.clientHeight;
    } else if (document.body) {
        windowWidth = document.body.clientWidth;
        windowHeight = document.body.clientHeight;
    }
    if (yScroll < windowHeight) {
        pageHeight = windowHeight;
    } else {
        pageHeight = yScroll;
    }
    if (xScroll < windowWidth) {
        pageWidth = xScroll;
    } else {
        pageWidth = windowWidth;
    }
    arrayPageSize = new Array(pageWidth, pageHeight, windowWidth, windowHeight);
    return arrayPageSize;
}

