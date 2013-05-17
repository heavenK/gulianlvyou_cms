
var css_width = 0;
var css_height = 0;
var pagesize = getPageSize();
var pageWidth = pagesize[0];
var pageHeight = pagesize[1];
var ScreenWidth = pagesize[2];
var ScreenHeight = pagesize[3];
css_width = (ScreenWidth - 600) / 2;
css_height = (ScreenHeight - 350) / 2;
//$("#Popupbox_bg").css({ "width": pageWidth, "height": pageHeight, "opacity": "0.8" });

function showbox() {

    jQuery("#Popupbox").fadeIn("fast", function() {
        jQuery("#Popupbox_bg").fadeIn("fast");
        jQuery("body").attr("overflow", "hidden");
    })

    jQuery("#Popupbox .shutdown").click(function() {
        jQuery("#Popupbox").fadeOut("fast", function() {
            jQuery("#Popupbox_bg").fadeOut("fast");
            jQuery("body").attr("overflow", "auto");
        })
    })   
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

function doloading(){
	if (document.documentElement && document.documentElement.scrollTop) {
		var scorll = document.documentElement.scrollTop;
	} else if (document.body) {
		var scorll = document.body.scrollTop;
	}
	var realheight = scorll + css_height;
	jQuery("#Popupbox").css({ "top": realheight, "left": css_width });

	showbox();
}






