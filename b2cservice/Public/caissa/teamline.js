/*
* ���ڲ���������ҳ��������
*
*/

var arrgroup;
var arrlinegroup = new Array();
var nowgroup = null;
var arrline = null;
var otherdepture = null;
function loadgroup(lineid) {
	url = 'http://www.myerpcenter.com/index.php?s=/Connection/getxianlubyID/erpxianluID/' + _lineid;
	jQuery.getJSON(url +"&jsoncallback=?", function(data){  
        if (data.status == 1) {
			
            arrgroup = data.data;
            otherdepture = data.other;
            arrline = data.line;
			
			arrlinegroup = arrgroup;
			nowgroup = arrlinegroup[0];

            initcalendar();

        }
        else {
            alert("����·�����������ѹ�������ֹ���ڣ�ϵͳ���Զ���ת����ҳ!"); window.location.href = "http://xxx";
        }
    });
}

loadgroup(_lineid);

function getgroupbydate(group, date) {
    var ret = null;
    for (var i = 0; i < group.length; i++) {
        if (group[i].date == date) {
            if (ret == null || group[i].person < ret.person) ret = group[i];
        }
    }
    if (ret != null && ret.num == 0) {
        for (var i = 0; i < group.length; i++) {
            if (group[i].date == date) {
                if (group[i].num > 0) {
                    ret = group[i]; break;
                }
            }
        }
    }
    return ret;
}

function getgroupbyid(group, id) {
    for (var i = 0; i < group.length; i++) {
        if (group[i].groupid == id) {
            return group[i];
        }
    }
    return null;
}

//��ʼ������Ԥ��������Ϣ
function initFloatDiv() {
    if (arrgroup == null || arrgroup.length == 0) { alert("����·���޳��ţ���ѡ��������Ʒ"); return; }

    var tmp = "";
    $("#Popupbox select").empty();
    for (var i = 0; i < arrlinegroup.length; i++) {
        tmp = "<option value='" + arrlinegroup[i].groupid + "'";
        if (nowgroup.groupid == arrlinegroup[i].groupid) {
            tmp += " selected='true'";
        }
        tmp += ">" + arrlinegroup[i].date + "���������ۼۣ���" + arrlinegroup[i].person;
        $("#Popupbox select").append(tmp);
    }
    initFloatPerson(nowgroup);
    $("#Popupbox select").bind("change", function() { initFloatPerson(arrlinegroup[$("#Popupbox select").get(0).selectedIndex]); });

    if (nowgroup.child == 0) {
        $("#Popupbox ul li:last-child").remove();
        $(".stastics span").remove();
    }

}

function initFloatPerson(group) {
    $(".pro_table td:first").html(group.date);
    $(".pro_table td:eq(1)").html(group.enddate);
    $(".pro_table td:last").html((group.num > 9 ? ">9" : (group.num <= 0 ? 0 : group.num)));
    $(".tuanhao").remove();
    $("<div class='tuanhao'>�Ŷӱ�ţ�" + group.erpno + "</div>").insertBefore(".pro_table");

    //ɾ��ԭ�е�   ����°�
    $("#Popupbox ul").remove();
    var text = "";
	text += '<ul><li class="pwidth">��׼���ۼۣ�</li><li class="pwidth2"><b class="piaohong">��' + group.person + '</b></li>'
		+ '<li> <span>���ˣ�</span> <span class="add_count"><input type="text" value="1"  /><span class="less"></span> <span class="add"></span> </span> <span>&nbsp;��</span> </li>'
		+ '<li> <span>��ͯ��</span> <span class="add_count"><input type="text" value="0"  /><span class="less"></span> <span class="add"></span> </span> <span>&nbsp;��</span> </li></ul>';
    $(text).insertAfter(".pro_table");
    $("#Popupbox ul input").bind("keyup", function() { this.value = this.value.replace(/\D/g, ''); if (this.value == "") this.value = "0"; computeprice(this, 1); });
    $("#Popupbox ul input").bind("afterpaste", function() { this.value = this.value.replace(/\D/g, ''); if (this.value == "") this.value = "0"; computeprice(this, 1); });
    $("#Popupbox ul span .add").bind("click", function() { var inp = $(this).parent().find("input").eq(0); inp.val(parseInt(inp.val()) + 1); computeprice(this, 2); });
    $("#Popupbox ul span .less").bind("click", function() { var inp = $(this).parent().find("input").eq(0); if (parseInt(inp.val()) <= 0) inp.val(0); else inp.val(parseInt(inp.val()) - 1); computeprice(this, 2); });

    //���ñ�����ֹ����Ϣ
    var now = new Date();
    var arrenddate = group.enddate.split('-');
    if (arrenddate[0] == now.getFullYear() && parseInt(arrenddate[1]) == now.getMonth() + 1 && parseInt(arrenddate[2]) == now.getDate() && now.getHours() > 10) {//����10��ǰ
        $(".pro_table th:eq(1)").html("<em class='getred_bg'>�������Ͻ�ֹ����</em><div>"
                + "<b>�Բ������Ѿ������ѱ���ʱ�䣬��ѡ�����������������飬���������Ա�ǩ֤����ѡ�������������������ѯ010-85906636��</b>"
                + "</div>");

    } else {
        $(".pro_table th:eq(1)").html("<em>�������Ͻ�ֹ����</em><div>"
                + "<b>Ϊȷ������˳��Ԥ������������ֹ�յ�������10��֮ǰ���������ύȫ��ǩ֤������ϣ����򽫻�Ӱ�쵽���������У�лл��</b>"
                + "<p>�����������δ���ڽ�ֹ�յ�������10��֮ǰ�ݽ�ȫ��ǩ֤���ϵģ��뼰ʱ���������ι�����ϵ��</p>"
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


/*��ʼ������*/
var calen = $("#calendar");
var nowYear, nowMonth, nowDay;
var arrDay = new Array(); // ����ÿһ��
function initcalendar() {
    //�����ؼ���ǰ �� ��  ѡ������
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

//���������ؼ�body
function adjustCalendar() {
    //��ֵ
    for (var i = 0; i < 42; i++) arrDay[i] = "";
    for (var i = 0; i < new Date(nowYear, nowMonth, 0).getDate(); i++) arrDay[i + new Date(nowYear, nowMonth - 1, 1).getDay()] = i + 1;

    calen.find("tr[use=day]").remove();

    //���ɵ�������
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
            else if (groupday.num <= 0) //����
                strbody += "<td class='has_pro'>" + arrDay[i * 7 + j] + "<span class='pro_state'>����</span><span class='detail_data'></span><span class='pro_price'><em class='f5'>��</em>" + groupday.person + "</span></td>\n";
            else {
				strbody += "<td class='" + (groupday == nowgroup ? "current " : "") + "has_pro' use='group'>" + arrDay[i * 7 + j] + "<span class='pro_state'>ʣ��</span><span class='detail_data'>" + (groupday.num > 9 ? ">9" : groupday.num) + "</span><span class='pro_price'><em class='f5'>��</em>" + groupday.person + "</span></td>\n";
            }
        }
        strbody += "</tr>\n";
    }
    calen.append(strbody);
    calen.find("td[use=tip]").attr("title", "�������г��뵱ǰҳ�г��в��죬\r\n�������ڲ鿴�����г�");
    $("#calendar tr:gt(0)").find("td:nth-child(1)").addClass("bg01");
    $("#calendar tr:gt(0)").find("td:nth-child(2)").addClass("bg02");
    $("#calendar tr:gt(0)").find("td:nth-child(3)").addClass("bg02");
    $("#calendar tr:gt(0)").find("td:nth-child(4)").addClass("bg02");
    $("#calendar tr:gt(0)").find("td:nth-child(5)").addClass("bg02");
    $("#calendar tr:gt(0)").find("td:nth-child(6)").addClass("bg02");
    $("#calendar tr:gt(0)").find("td:nth-child(7)").addClass("bg01");

    //���ÿɵ�� ����δ���ĵ�Ԫ��
    calen.find('td[use=group]').bind('click', function(evt) {
        calen.find('td').each(function(i) {
            $(this).removeClass("current");
        });
        $(this).addClass("current");
        nowgroup = getgroupbydate(arrgroup, getFullDate(nowYear, nowMonth, parseInt($(this).text())));
        $(".select_03 span").html("�������ڣ�" + nowgroup.date + "<br/>�Ŷӱ�ţ�" + nowgroup.erpno);
        $("a[rel='print']").attr("href", _lineid + "_trip.html?d=" + nowgroup.date + "&p=" + nowgroup.person + "&g=" + nowgroup.erpno);
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
    $("#Popupbox_bg").css({ "width": pageWidth, "height": pageHeight, "opacity": "0.8" });

    $(".book_03").click(function() {
        if (document.documentElement && document.documentElement.scrollTop) {
            var scorll = document.documentElement.scrollTop;
        } else if (document.body) {
            var scorll = document.body.scrollTop;
        }
        var realheight = scorll + css_height;
        $("#Popupbox").css({ "top": realheight, "left": css_width });

		showyudingdiv();
    })
})

function showyudingdiv() {
    initFloatDiv();

    $("#Popupbox").fadeIn("fast", function() {
        $("#Popupbox_bg").fadeIn("fast");
        $("body").attr("overflow", "hidden");
    })

    $("#Popupbox .shutdown").click(function() {
        $("#Popupbox").fadeOut("fast", function() {
            $("#Popupbox_bg").fadeOut("fast");
            $("body").attr("overflow", "auto");
        })
    })   
}

var totalnum = 0;
var clickurl = "";
var oldAgreevisa = false;
function computeprice(ctrl, type) {
    var group = getgroupbyid(arrlinegroup, $("#Popupbox select").val());

    var pernum = 0;
    var childnum = 0;
    var pernumVisa = 0;
    var childnumVisa = 0;

	pernum = parseInt($("#Popupbox ul:first input:first").val());
	if (group.child > 0) {
		childnum = parseInt($("#Popupbox ul:first input:last").val());
	}
	if (pernum == 0) {
		$("#Popupbox ul input:first").val(1);
		pernum = 1;
	}
    var text = "";
        text = '<div class="stastics">��ѡ���ˣ���׼���ۼ۳���<em>' + pernum + '</em>����ͯ<em>' + childnum + '</em>���ϼƣ�<em class="f1">��</em><em class="f2">' + (group.person * pernum + group.child * childnum) + '</em></div>';
    
	$(".stastics").remove();
    $(".heji").remove();
    $(".youqingtips").remove();
    $(text).insertAfter("#Popupbox ul:last");

    totalnum = pernum + childnum;
    clickurl = "http://www.myerpcenter.com/index.php?s=/Yuding/addDingdan/chanpinID/"+group.groupid;
    if (totalnum > group.num || group.num == 0 || pernum == 0) {
        $("#Popupbox .nextstep input").attr("src", "./�¹�10��ȫ��֮��-��������-����������2_files/xiayibu_gray.gif");
        $("#Popupbox .nextstep input").unbind().bind("click", function() { alert("����Ԥ�����������ɳ������ŵ�ʣ������,���ҳ���������Ϊ0��"); });
    }
    else {
        $("#Popupbox .nextstep input").attr("src", "./�¹�10��ȫ��֮��-��������-����������2_files/nextstep.gif");
        $("#Popupbox .nextstep input").unbind().bind("click", function() {
			
            $("#Popupbox").fadeOut("fast", function() {
                $("#Popupbox_bg").fadeOut("fast");
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

function getentityUrl(id) {
    for (var i = 0; i < arrline.length; i++) {
        if (arrline[i].id == id)
            return arrline[i].url;
    }
    return "";
}

