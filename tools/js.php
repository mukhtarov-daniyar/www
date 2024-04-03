function _sys_check_int(val)
{
	val = ""+val+"";
	var l=val.length;

	for (i=0; i<l && val.charAt(i)==" "; i++);

	if (val.charAt(i)=='+' || val.charAt(i)=='-')
		i++;

	for (; i<l && val.charAt(i)!=" "; i++)
		if (val.charAt(i)<'0' || val.charAt(i)>'9')
			return false;

	for (i; i<l && val.charAt(i)==" "; i++);
	
	if (i != l)
		return false;

	return true;
}

function _sys_check_uint(val)
{
	if (_sys_check_int(val) && val>=0)
		return true;
	return false;
}

function _sys_check_float(val)
{
	val = ""+val+"";
	var l=val.length;
	var dot = 0;

	for (i=0; i<l && val.charAt(i)==" "; i++);

	if (val.charAt(i)=='+' || val.charAt(i)=='-')
		i++;

	for (; i<l && val.charAt(i)!=" "; i++)
	{
		if (val.charAt(i)=='.')
			dot++;
		if ( ((val.charAt(i)<'0' || val.charAt(i)>'9') && val.charAt(i)!='.') || 
			 (dot>1 && val.charAt(i)=='.') )
			return false;
	}

	for (i; i<l && val.charAt(i)==" "; i++);
	
	if (i != l)
		return false;

	return true;
}

function _sys_check_ufloat(val)
{
	if (_sys_check_float(val) && val>=0)
		return true;
	return false;
}



function _sys_find_select(selName, formName)
{
	var f = document.forms[formName];
	if ((""+f)=='undefined')
		f = document._sys_form_new;
	var obj = f[selName];
	var value = f[selName+"_find"].value.toUpperCase();

	for (i=obj.selectedIndex+1; i<obj.options.length; i++)
	{
		if (obj.options[i].text.toUpperCase().indexOf(value) != -1)
		{
			obj.selectedIndex = i;
			if (obj.onchange)
				obj.onchange();
			return;
		}
	}
	if (confirm("Ничего не найдено. Начать поиск с начала списка?"))
	{
		obj.selectedIndex = -1;
		_sys_find_select(selName, formName)
	}
}

function checkLength(name, len)
{
	var v = document.getElementById(name).value;
	var l = v.length;
	var s = "";

	if (l > len)
	{
		alert("Превышение длины поля!\n("+len+" символов)");
		s = v.substring(0, len);
		document.getElementById(name).value = s;
	}
}

var _sys_form_field_name;
function sf(name)
{
alert('fgfdgdg');
		$('<div id="selectFile" />').dialogelfinder({
			url : '/admin/php/connector.minimal.php',
			lang : 'ru',
			commandsOptions: {
				getfile: {
					oncomplete: 'destroy' // destroy elFinder after file selection
				}
			}
		});
		
		e.preventDefault();
}
function sf2(img)
{
	var o = document.getElementById(_sys_form_field_name);
	if (o.id != 'gallery')
		o.value = img;
    else {
        if(o.value) dot = ','; else dot = '';
        o.value +=dot+img;    
        }

}
function su(name, url)
{
	_sys_form_field_name = name;
	w = window.open("/admin/tools/_sys_insert_url.php?url="+url+"&func=sf2", "selectUrl", "width=350,height=100,left=300,top=180,toolbar=0,scrollbars=0,menubar=0,resizable=0,status=0");
	w.focus();
}
function sp(name)
{
	_sys_form_field_name = name;
	w = window.open("/admin/tools/_sys_select_page.php?func=sf2", "selectPage", "width=500,height=600,left=200,top=100,toolbar=0,menubar=0,resizable=1,status=0,scrollbars=1");
	w.focus();
}
function demo()
{
	alert("Sorry, this is a DEMO version only.");
	return;
}

function getSelectedIndexes (oListbox)
{
  var arrIndexes = new Array;
  for (var i=0; i < oListbox.options.length; i++)
  {
      if (oListbox.options[i].selected) arrIndexes.push(i);
  }
  return arrIndexes;
};

