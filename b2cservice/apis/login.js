/*
* 用于弹出层 注册、登录
*
*/

var success_method = "";
function judge(method) {
    success_method = method;
	url = SITE_INDEX+"DEDEInfo/ajax_loginsta";
	jQuery.getJSON(url +"&jsoncallback=?", function(data){  
		   if (data.mid > 0) {
			   //已登录
			   eval(method);
		   } else {
			   //显示  注册登录浮层
			   showlogregisdiv();
		   }
    });
	
		 
}

function showlogregisdiv() {
    var div = $("#logregisdiv");
    if (div.length == 0) {
        div = $('<div class="login_tanchu" id="logregisdiv"></div>');
        $('body').append(div);
		var text = '<span>'
			+ '<input type="hidden" id="fmdo" name="fmdo" value="login">'
			+ '<input type="hidden" id="dopost" name="dopost" value="login">'
			+ '<input type="hidden" id="keeptime" name="keeptime" value="604800">'
			+ '<em><a href="javascript:closeDiv(\'logregisdiv\');"></a></em>'
			+ '<h1></h1>'
			+ '<ul>'
			+ '<li><label for="username">账号</label><input id="txtUsername" class="text login_from" type="text" name="userid"/></li>'
			+ '<li><label for="password">密码</label><input id="txtPassword" class="text login_from2" type="password" name="pwd"/></li>'
			+ '</ul>'
			+ '<dl>'
			+ '<dd><a href="javascript:do_login()"></a></dd>'
			+ '<dt><a href="'+ROOT_URL+'member/index_do.php?fmdo=user&dopost=regnew" target="_blank"></a></dt>'
			+ '</dl>'
			+ '</span>';
        div.html(text);
    }
	
	
    floatDiv("logregisdiv");	
    $("#loginPass").bind(($.browser.opera ? "keypress" : "keydown"), function(event) { if (event.keyCode == 13) do_login(); });

}


function gulian_directorder() {
    eval(success_method);
}

function do_login() {
    var userid = $("#txtUsername").val();
    var pwd = $("#txtPassword").val();
    var fmdo = $("#fmdo").val();
    var dopost = $("#dopost").val();
    var keeptime = $("#keeptime").val();
    if (userid == null || userid == "") {
        alert("请输入用户名!");
        return;
    }
    if (pwd == null || pwd == "") {
        alert("请输入密码!");
        return;
    }
	jQuery.getJSON(B2CSERVICE_URL+"apis/action_index_do.php?userid="+userid+"&pwd="+pwd+"&fmdo="+fmdo+"&dopost="+dopost+"&keeptime="+keeptime+"&jsoncallback=?", function(data){
		if(data.suc == 1){
			$("head").append(""+data.code);
			eval(success_method);
		}
		else{
			alert("登录失败:"+data.msg);
		}
    });
}

//浮层
function floatDiv(showDivId) {
    var bg = $("#mybg");
    if (bg.length == 0) {
        bg = $("<div class='layerbg' id ='mybg'></div>");
        $('body').append(bg);
    }
    bg.height(Math.max(document.body.scrollHeight, document.documentElement.scrollHeight));
    bg.show();
    centerDiv($("#" + showDivId));
}

function closeDiv(hidDivId) {
    $("#" + hidDivId).hide();
    $("#mybg").hide();
    document.body.style.overflow = "auto";
}

function centerDiv(obj) {
    $(obj).show();
    center(obj);
    $(window).scroll(function() {
        center(obj);
    });
    $(window).resize(function() {
        center(obj);
    });
}

function center(obj) {
    var windowWidth = document.documentElement.clientWidth;
    var windowHeight = document.documentElement.clientHeight;
    var popupHeight = $(obj).height();
    var popupWidth = $(obj).width();
    $(obj).css({
        "position": "absolute",
        "top": (windowHeight - popupHeight) / 2 + $(document).scrollTop(),
        "left": (windowWidth - popupWidth) / 2
    });
}