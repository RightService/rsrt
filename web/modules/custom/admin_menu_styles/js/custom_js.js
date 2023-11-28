(function($){
	$(document).ready(function(){
		$("#toolbar-item-toolbar-menu-rsrt-tray .toolbar-menu-administration li").hover(function(){
			if($(this).hasClass("menu-item--expanded")){
				if($("#toolbar-item-toolbar-menu-rsrt-tray").hasClass("toolbar-tray-horizontal")) {
					if($("body").hasClass("front")) {
						$(this).find("ul").css({"display": "block", "position": "absolute", "top": "50px"});
					}else{
						$(this).find("ul").css({"display": "block", "position": "absolute", "top": "35px"});
					}
					//$(this).find("ul").addClass("navbar-tray");
				}else{
					$(this).find("ul").css({"display": "block", "position": "static"});
				}
			}
		}, function(){
			if($(this).hasClass("menu-item--expanded")){
				$(this).find("ul").css("display", "none");
			}
		});
	});
})(jQuery);