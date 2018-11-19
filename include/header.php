<?php
if(isset($_GET['msg'])){
	$msg = base64_decode($_GET['msg']);
}
if(isset($_GET['error'])){
	$error = base64_decode($_GET['error']);
}

?>
<!DOCTYPE html>
<html>
<head>

<!--
	e-menu - On-they-fly menu editor (ghimire) 
	
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>. 
-->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>e-menu (on-the-fly HTML5/CSS3 based php menu editor) by ghimire</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<script src="js/jquery-1.6.2.min.js"></script>

<link href="js/facybox/facybox.css" media="screen" rel="stylesheet" type="text/css" />
<script src="js/facybox/facybox.js" type="text/javascript"></script>

<script>
$(document).ready(function(){

    $('a[rel*=facybox]').facybox({
        // noAutoload: true
      });
	
	$('table tr.d1').hover(function(){
		$(this).children().addClass('rowhighlight');
	},function(){
		$(this).children().removeClass('rowhighlight');
	});
	
	$('.menumsg').click(function(){
		$(this).remove().slow();
	});
	$('.menuerror').click(function(){
		$(this).remove().slow();
	});

	$('ul.menustyle').children(":first").addClass("current");
	
	$('input[name=switchmenu]').change(function(){
		if ($("input[@name=switchmenu]:checked").val()){
			$(".menustyle").attr("id",$("input[@name=switchmenu]:checked").val());
			$.get("menu_edit.php", { settheme: $("input[@name=switchmenu]:checked").val()});
		}
	});

	$('a#listtoggle').toggle(function(){
		$("#menu_list_editor").children(":first-child").hide();
		$("#menu_buttons").hide();
		$(this).html("Show List");	
	},function(){
		$("#menu_list_editor").children(":first-child").show();
		$("#menu_buttons").show();
		$(this).html("Hide List");
	});

	$(".menu_form_content input[type=text]").hover(function(){
			$(this).css("border","1px solid #0186ba");
		},function(){
			$(this).css("border","1px solid #c0c0c0");
		});
	$(".menu_form_content input").focus(function(){
		$(this).css("border","1px solid #0186ba");
	});
	$(".menu_form_content input").blur(function(){
		$(this).css("border","1px solid #c0c0c0");
	});

	
	
});
</script>
</head>
<body>
<?php
	if(substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1) != "menu_edit.php") { 
?>
<article class="menu_main">
<nav class="menu_header">
		<?php include("menu_display.php"); ?>
</nav>
<section class="menu_main_container">
<?php
	if (isset($msg))
	{
		echo '<span class="menumsg">'. $msg .' <img id="delete_msg" src="images/delete.png"></span>';
	}
	if (isset($error))
	{
		echo '<span class="menuerror">'. $error .' <img id="delete_error" src="images/delete.png"></span>';
	}
}

?>