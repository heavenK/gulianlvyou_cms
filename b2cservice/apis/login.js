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
					
//        var text = '<div class="Pop1" style="width:300px; height:300px; background:#000; float:left"><div class="Pop_title1"><span class="Pop_xx"><a href="javascript:closeDiv(\'logregisdiv\');">关闭</a></span></div>'
//                    + '<div class="Pop_Content2">此预订方式将无法享受<br>凯撒会员相关优惠<a href="javascript:gulian_directorder()" class="Direct_booking">直接预订</a></div>'
//                    + '<div class="zcdl_right"><table cellspacing="0" cellpadding="0" border="0" width="236" id="zcdl_bt2">'
//                    + '<tr><td width="57">用户名：</td><td width="179"><input type="text" class="zcdl_x" id="loginName" name="loginName"></td></tr><tr><td>密&nbsp;&nbsp;&nbsp;&nbsp;码：</td>'
//                    + '<td><input type="password" class="zcdl_x" id="loginPass" name="loginPass"></td></tr>'
//                    + '</table><div style="margin:0 10px;"><div class="zcdl_right_an"><a href="javascript:do_login()">登录</a></div><em></em></div>'
//                    + '<div class="zcdl_an"><a href="http://xxxxxxxxxx" target="_blank">注册</a></div></div></div>';
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
//    if (uname == null || uname == "") {
//        alert("请输入用户名!");
//        return;
//    }
//    if (psw == null || psw == "") {
//        alert("请输入密码!");
//        return;
//    }'
	jQuery.getJSON(B2CSERVICE_URL+"apis/action_login.php?userid="+userid+"&pwd="+pwd+"&fmdo="+fmdo+"&dopost="+dopost+"&keeptime="+keeptime+"&jsoncallback=?", function(data){
		
		$("head").append(""+data.code); 
		
		//alert(data.code);
    });
	
	
//    jQuery.getJSON(SITE_INDEX+"My/ajax_login/uname/" + uname + "/password/" + psw + "/jsoncallback/?",
//         function(data) {
//             if (data.mid > 0) {
//                 eval(success_method);
//             } else {
//                 alert("登录失败:请确保用户名密码正确！");
//             }
//         });


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