// JScript 文件
String.prototype.Trim = function() 
{ 
    return this.replace(/(^\s*)|(\s*$)/g, ""); 
}  
String.prototype.LTrim = function() 
{ 
    return this.replace(/(^\s*)/g, ""); 
}  
String.prototype.RTrim = function() 
{ 
    return this.replace(/(\s*$)/g, ""); 
} 
/*******************************************
参数说明:
strId:控件ID
strPrompt:提示信息
strIsNull:是否为空 T/F
*******************************************/
//空值
function isNull(strId,strPrompt) 
{
    try
    {
        if(document.getElementById(strId).value.Trim().length ==0)
        {
            document.getElementById(strId).select();
            alert(strPrompt+'不能为空,请重新输入！');
            return false;
        }
        else
            return true;    
    }
    catch(e)
    {
        alert(document.getElementById('code').value);
        alert(e.message);
    }
}
//短日期，形如 (2003-12-05) 
function isDate(strId,strPrompt,strIsNull) 
{ 
    if(strIsNull == 'T' && document.getElementById(strId).value.length == 0) return true;
    var reg = /^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/;
    var r = document.getElementById(strId).value.match(reg); 
    if(r==null)
    {
        document.getElementById(strId).select();
        alert(strPrompt+'输入错误，正确格式如：2009-01-01,请重新输入！');
        return false; 
    }
    else
    {
        return true;
    }
} 
//长时间，形如 (2003-12-05 13:04:06) 
function isDateTime(strId,strPrompt,strIsNull) 
{ 
    if(strIsNull == 'T' && document.getElementById(strId).value.length == 0) return true;
    var reg = /^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/; 
    var r = document.getElementById(strId).value.match(reg); 
    if(r==null)
    {
        document.getElementById(strId).select();
        alert(strPrompt+'输入错误，正确格式如：2009-01-01 00:00:00,请重新输入！');
        return false;     
    }
    else
    {
        return true;
    }
}
//验证整数
function isInt(strId,strPrompt,strIsNull) 
{ 
    if(strIsNull == 'T' && document.getElementById(strId).value.length == 0) return true;
    var ret = true;
    if(!document.getElementById(strId).value) 
        ret = false; 
    var strP=/^\d+(\.\d+)?$/; 
    if(!strP.test(document.getElementById(strId).value)) 
        ret = false; 
    try{ 
        if(parseInt(document.getElementById(strId).value)!=document.getElementById(strId).value) 
            ret = false; 
    } 
    catch(ex) 
    { 
        ret = false; 
    } 
    if(!ret)
    {
        document.getElementById(strId).select();
        alert(strPrompt+'输入整数格式错误，请重新输入！');
    }
    return ret;
}
//验证浮点数
function isFloat(strId,strPrompt,strIsNull) 
{ 
    if(strIsNull == 'T' && document.getElementById(strId).value.length == 0) return true;
    var ret = true;
    if(!document.getElementById(strId).value) 
        ret = false; 
    var strP=/^\d+(\.\d+)?$/; 
    if(!strP.test(document.getElementById(strId).value)) 
        ret = false; 
    try{ 
        if(parseFloat(document.getElementById(strId).value)!=document.getElementById(strId).value) 
            ret = false; 
    } 
    catch(ex) 
    { 
        ret = false; 
    } 
    if(!ret)
    {
        document.getElementById(strId).select();
        alert(strPrompt+'输入小数格式错误，请重新输入！');
    }    
    return ret;        
}
//验证邮件格式
function isEmail(strId,strPrompt,strIsNull)
{
	if(strIsNull == 'F' && !document.getElementById(strId).value ) 
		return true;
   // var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
    var reg = /^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/;
    if(!reg.test(document.getElementById(strId).value))
    {
        document.getElementById(strId).select();
        alert(strPrompt+'输入错误，正确格式如：aver@nwnu.edu.cn，请重新输入！');
        return false;             
    }
    else
    {
        return true;
    }
}

//验证身份证号码
function isIdCard(strId,strPrompt,strIsNull)
{
    if(strIsNull == 'F' && !document.getElementById(strId).value ) 
		return true;
    var reg = /^\d{15}(\d{2}[A-Za-z0-9])?$/;
    if(!reg.test(document.getElementById(strId).value))
    {
        document.getElementById(strId).select();
        alert(strPrompt+'输入错误，正确格式为15位或18位，请重新输入！');
        return false;             
    }
    else
    {
        return true;
    }
}

//验证邮政编码
function isPostCode(strId, strPrompt, strIsNull) {
    if (strIsNull == 'T' && document.getElementById(strId).value.length == 0) return true;
    var reg = /^\d{6}(\d{2}[A-Za-z0-9])?$/;
    if (!reg.test(document.getElementById(strId).value)) {
        document.getElementById(strId).select();
        alert(strPrompt + '输入错误，正确格式为6位，请重新输入！');
        return false;
    }
    else {
        return true;
    }
}
//验证电话号码
function isPhone(strId,strPrompt,strIsNull)
{
    if(strIsNull == 'T' && document.getElementById(strId).value.length == 0) return true;
    var reg = /^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/;
    if(!reg.test(document.getElementById(strId).value))
    {
        document.getElementById(strId).select();
        alert(strPrompt+'输入错误，正确格式为：8707185或0931-8707185，请重新输入！');
        return false;             
    }
    else
    {
        return true;
    }
}
//验证手机号码
function isMobile(strId,strPrompt,strIsNull)
{
    if(strIsNull == 'F' && !document.getElementById(strId).value) 
		return true;
    var reg =  /^(((13[0-9]{1})|15[0-9]{1}|18[0-9]{1})+\d{8})$/;
    if(!reg.test(document.getElementById(strId).value))
    {
        document.getElementById(strId).select();
        alert(strPrompt+'输入错误，请重新输入！');
        return false;             
    }
    else
    {
        return true;
    }
}
function isBetween( strId, strPrompt, strIsNull, min, max )
{
	if(isInt(strId, strPrompt, strIsNull))
	{
		value = parseInt(document.getElementById(strId).value);
		if( value < min || value > max )
		{
			document.getElementById(strId).select();
			alert(strPrompt+'输入错误，超出最小值'+min+'和最大值'+max+'范围！');
			return false ;
		}
		return true ;
	}
	return false ;
}