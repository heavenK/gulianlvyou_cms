/*
* ���ڵ����� ע�ᡢ��¼
*
*/

var success_method = "";
function judge(method) {
    success_method = method;
	url = 'http://www.myerpcenter.com/index.php?s=/Server/getinfo';
	jQuery.getJSON(url +"&jsoncallback=?", function(data){  
		   if (data.uid > 0) {
			   //�ѵ�¼
			   eval(method);
		   } else {
			   //��ʾ  ע���¼����
			   showlogregisdiv();
		   }
    });
	
		 
		 
}

function showlogregisdiv() {
    var div = $("#logregisdiv");
    if (div.length == 0) {
        div = $('<div class="logregisdiv" id="logregisdiv"></div>');
        $('body').append(div);

        var text = '<div class="Pop1"><div class="Pop_title1"><span class="Pop_xx"><a href="javascript:closeDiv(\'logregisdiv\');">�ر�</a></span></div>'
                    + '<div class="Pop_Content2">��Ԥ����ʽ���޷�����<br>������Ա����Ż�<a href="javascript:caissa_directorder()" class="Direct_booking">ֱ��Ԥ��</a></div>'
                    + '<div class="zcdl_right"><table cellspacing="0" cellpadding="0" border="0" width="236" id="zcdl_bt2">'
                    + '<tr><td width="57">�û�����</td><td width="179"><input type="text" class="zcdl_x" id="loginName" name="loginName"></td></tr><tr><td>��&nbsp;&nbsp;&nbsp;&nbsp;�룺</td>'
                    + '<td><input type="password" class="zcdl_x" id="loginPass" name="loginPass"></td></tr>'
                    + '</table><div style="margin:0 10px;"><div class="zcdl_right_an"><a href="javascript:caissa_login()">��¼</a></div><em></em></div>'
                    + '<div class="zcdl_an"><a href="http://xxxxxxxxxx" target="_blank">ע��</a></div></div></div>';

        div.html(text);
    }
    $("#loginPass").bind(($.browser.opera ? "keypress" : "keydown"), function(event) { if (event.keyCode == 13) caissa_login(); });
	//stat code
    _gaq.push(['_trackPageview','/virtual/loginorregist']);
    floatDiv("logregisdiv");
}


function caissa_directorder() {
    eval(success_method);
    _gaq.push(['_trackEvent', 'Register_Login', 'button','btn_direct_bookings']);
}

function caissa_login() {
    var email = $("#loginName").val();
    var psw = $("#loginPass").val();
    if (email == null || email == "") {
        alert("�������û���!");
        return;
    }
    if (psw == null || psw == "") {
        alert("����������!");
        return;
    }

    $(".zcdl_right_an a").addClass("no");
    $(".zcdl_right_an a").attr("href", "javascript:void()");

    $.getJSON("http://bbs.caissa.com.cn/member.php?mod=logging&action=api_login&username=" + email + "&password=" + psw + "&callback=?",
         function(data) {
             if (data.uid > 0) {
                 if (typeof (__ozfac2) != 'undefined') {
                     //99click
                     __ozfac2("username=" + data.username + "&email=" + data.email, "#login");
                 }
                 eval(success_method);
             } else {
                 alert("��¼ʧ��:��ȷ���û���������ȷ��");
                 $(".zcdl_right_an a").removeClass("no");
                 $(".zcdl_right_an a").attr("href", "javascript:caissa_login()");
             }
         });
   _gaq.push(['_trackEvent', 'Register_Login', 'button','btn_Login']);
}

//����
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