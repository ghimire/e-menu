<?php
include_once 'include/config.php';

if (EDITOR_ENABLED) {
    if (isset($_GET['dir']) && isset($_GET['id'])) {
    	$dir = filter($_GET['dir']);
    	$id = intval($_GET['id']);
    
    	switch ($dir){
    		case 'up':
    			$swap = ($id > 1)? $id-- : 1;
    			break;
    
    		case 'down':
    		    $orderquery = "select MAX(`order`) as maxorder from menu where parent = ".intval($_GET['pid']).";";
    		    $orderresult = mysql_query($orderquery);
    		    $orderrow = mysql_fetch_row($orderresult);
    		    $maxorder = intval($orderrow[0]); mysql_free_result($orderresult);
    		    if ($id < $maxorder) {
        			$sql = "SELECT count(*) FROM menu";
        			$result = mysql_query($sql) or die(mysql_error());
        			$r = mysql_fetch_row($result);
        			$max = intval($r[0]);
        			$swap = ($id < $max)? $id++ : $max;
        			mysql_free_result($result);
    		    } else {
    		        $swap = $id;
    		    }
    			break;
    
    		default:
    			$swap = $id;
    	}
    
    	$sql = "UPDATE `menu` SET `order` = CASE `order` WHEN $id THEN $swap WHEN $swap THEN $id END WHERE `order` IN ($id, $swap) and parent=".intval($_GET['pid']);
    	$result = mysql_query($sql) or die(mysql_error());
    
    	header("Location: menu_list.php");
    }
    
    
    include_once 'include/header.php';
    ?>
    <form name="menu_list" id="menu_list" action="menu_edit.php?action=delete" method="post">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
    		<tr width="100%">
    			<td>
    				<div id="menu_buttons" style="float: left; padding-top: 5px">
    					<a rel="facybox" href="menu_edit.php?task=add" class="menu_back_link" style="padding-right: 10px;">Add Menu</a>
    					<a href="#" class="menu_back_link" style="padding-right: 10px;" onClick="return menu_delete_id();">Delete Selected</a>
    				</div>
    				<div class="menu_back_link" style="float: right; padding-left: 10px; padding-right: 10px; margin-left: 10px;">
    					<a id="listtoggle" href="./" target="_blank" style="color: green; text-decoration: none">Hide List</a>
    				</div>
    				<div class="menu_back_link" style="float: right; padding-left: 10px; padding-right: 10px;">
    					Themes
    					<!--  http://www.red-team-design.com/css3-dropdown-menu -->
    					<input type="radio" name="switchmenu" value="blacktheme" <?php if (getTheme() == "blacktheme") echo "checked";?>>Black
    					<!-- http://www.webdesignerwall.com/demo/css3-dropdown-menu/ -->
    					<input type="radio" name="switchmenu" value="graytheme" <?php if (getTheme() == "graytheme") echo "checked";?>>Gray
    				</div>
    			</td>
    		</tr>
    
    		<tr id="menu_list_editor">
    			<td>
    				<div style="width: 960px; margin-top: 10px; border: 0px solid #c0c0c0">
    					<table cellspacing="0" cellpadding="0" class="menu_grid_table">
    						<tr class="header_title" align="center">
    							<td>
    								&nbsp;
    							</td>
    							<td style="padding-left: 10px">
    								<img src="images/check.png" height="20" width="20" border="0">
    							</td>
    							<td>&nbsp;&nbsp;&nbsp; Order</td>
    							<td>Menu Name</td>
    							<td>Description</td>
    							<td>Url</td>
    						</tr>
    						<?php getMenuList(0); ?>
    					</table>
    				</div>
    			</td>
    		</tr>
    		<tr><td>&nbsp;</td></tr>
    	</table>
    </form>
    
    <script type="text/javascript" language="javascript">
    function menu_delete_id(){
    	var count = 0;
    	
    	for (var i = 0; i < document.menu_list.elements.length ; i ++)
    	{	
    		if(window.document.menu_list.elements[i].id.substr(0,13)=='menu_check_id'){
    			if(window.document.menu_list.elements[i].checked==true){
    				count++;
    				break;
    			}
    		}
    	}
    	if(count==0){
    		alert('Please select Menu');
    	}
    	else
    	{
    		if(confirm("Do you really want to delete Menu?")){
    			window.document.menu_list.submit();
    		}
    		else{
    			return false;
    		}
    	}
    }
    </script>
    
    <?php include("include/footer.php"); 

} else {
    header("Location: ".INDEX_FILE);
    die();
}
?>