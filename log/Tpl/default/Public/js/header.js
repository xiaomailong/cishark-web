/**
 * 控制导航的主内容的装入
 */

jQuery(function($){
	/*开始设置导航背景*/
	$("#jwmainnav_root li").mouseover(function(){
	    $(this).addClass("hover_nav");
	}).mouseout(function(){
	    $(this).removeClass("hover_nav");
	});

	
	/*链接点击设置*/
	var $nav_link = $("#jwmainnav_root li a");
	
	$nav_link.click(function(){
		var $li = $(this).parent().parent().parent();
		if($li.hasClass("jwcurrent_nav") == true){
			return false;
		}
		
		$li.addClass("jwcurrent_nav")
		.siblings("li")
		.each(function(){
			if($(this).hasClass("jwcurrent_nav") == true){
				$(this).removeClass("jwcurrent_nav");
			}
		});
	
		/*点击后装入菜单*/
		//$("#menu_wrap").load("http://localhost/xbsd/index.php/Menu/menuView");
		//return false;
        return true;
	});	
});
