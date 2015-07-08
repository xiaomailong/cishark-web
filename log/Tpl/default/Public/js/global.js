/**
 * lisency
 * @date 9.10
 */

/*
 * global data field
 * */
var defaults_text = new Array();

/*this field we be defined the closure and global function*/

/**
 * @input input标签
 * @label input默认显示区域即label标签域
 * @text_name 与defaults_text中设置的一样，是该数组中项目的名称
 */
var fsetdefault = function(input,label,text_name){
	if(input.attr("value") == ""){
		label.html(defaults_text[text_name]);
	}
	input.blur(function(){
		 if(input.attr("value") == ""){
        label.html(defaults_text[text_name]);
        input.css("border-color","red");
    }
	}).focus(function(){
		label.html("");
        input.css("border-color","#fc0");
	});
};
var comment_dialog = function(data){

	art.dialog.through({
		title: "查看评语",
		content:data ,
		lock: true,
		width: 500,
		resize: false,
		opacity: 0.5,
		esc: true
	});
}
function confirm_delete(url){
    art.dialog.confirm("确认删除", function(){location.href = url;});
}

if(!Array.prototype.map)
Array.prototype.map = function(fn,scope) {
    var result = [],ri = 0;
    for (var i = 0,n = this.length; i < n; i++){
        if(i in this){
            result[ri++]  = fn.call(scope ,this[i],i,this);
        }
    }
    return result;
};
var getWindowWH = function(){
    return ["Height","Width"].map(function(name){
        return window["inner"+name] ||
        document.compatMode === "CSS1Compat" && document.documentElement[ "client" + name ] || document.body[ "client" + name ]
    });
};

(function($){
 $.fn.serializeJson=function(){
 var serializeObj={};
 var array=this.serializeArray();
 var str=this.serialize();
 $(array).each(function(){
     if(serializeObj[this.name]){
     if($.isArray(serializeObj[this.name])){
     serializeObj[this.name].push(this.value);
     }else{
     serializeObj[this.name]=[serializeObj[this.name],this.value];
     }
     }else{
     serializeObj[this.name]=this.value;
     }
     });
 return serializeObj;
 };
 })(jQuery);

/*DOM加载完成后执行任务*/
$(function(){
    /*
     * 系统初始化
     * 装入
     * */
	//$("#menu_wrap").load(MENU_URL,"",function(){
		//$("#content_wrap").load( $(".cur_fir_menu .cur_sec_menu").attr("href") ) ;
	//});
});
