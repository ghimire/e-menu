<?php 
include_once 'include/config.php';

function getChild($id){
	$id=intval($id);
	$result = mysql_query("SELECT * FROM menu WHERE parent=".$id." order by `order`");

	while($row = mysql_fetch_object($result))
	{
		mysql_query("DELETE FROM menu WHERE menu_id = ".intval($row->menu_id));
		getChild(intval($row->menu_id));
	}
	
	mysql_free_result($result);
}

if(isset($_GET['action']) && $_GET['action'] == 'delete' && EDITOR_ENABLED){
	$message = "";
	$delete_result = 0;
	
	if( ($_POST['menu_check_id'] != '') && (is_array($_POST['menu_check_id'])) ){
		foreach($_POST['menu_check_id'] as $id){
			if(intval($id)) {
				$delete_result = mysql_query("DELETE FROM menu WHERE menu_id = ".$id);
				if($delete_result > 0)
				{
					$message = base64_encode("Menu Deleted Successfully.");
				}
				getChild($id);
			}
		}
	}
	if(!empty($message)) { 	header("Location: menu_list.php?msg=".$message); die(); }
}

if(isset($_GET['settheme']) && EDITOR_ENABLED) {
    $themes = array('blacktheme', 'graytheme');
    $themename = $_GET['settheme']; 
    if(in_array($themename, $themes)) {
        mysql_query("UPDATE menutheme set theme = '".$themename."';");
    }
} 

if(isset($_POST['save']) && EDITOR_ENABLED){
	$disp_name = $_POST['disp_name'];
	$disc = $_POST['description'];
	$url = $_POST['url'];
	$parent_level = $_POST['disp_location'];

	if($parent_level == '0_0'){
		$parent = '0';
		$sublevel = '0';
	}
	else {
		$arr = explode('_',$parent_level);
		$parent = $arr[0];
		$sublevel = $arr[1]+1;
	}

	$select_menu = "SELECT MAX(`order`) as maxrecord FROM menu WHERE parent=".$parent;
	$result = mysql_query($select_menu);

	if($result && mysql_num_rows($result) > 0){
		$row = mysql_fetch_assoc($result);
		$maxrecord = $row['maxrecord'];
		$order = $maxrecord + 1;
		mysql_free_result($result);
	}
	else{
		$order = '1';
	}

	if(isset($_POST['action']) && ($_POST['action'] == 'add'))
	{
		$fields = array("display_name","description","url","parent","sublevel","`order`");
		$values = array("'$disp_name'","'$disc'","'$url'","$parent","$sublevel","$order");

		$result = table_insert("menu",$fields,$values);
		$insert_id = mysql_insert_id();
	}

	if(isset($_POST['action']) && ($_POST['action'] == 'edit'))
	{
		$menu_id = (isset($_POST['eid'])?intval($_POST['eid']):-1);

		$fields = array("display_name","description","url","parent","sublevel");
		$values = array("'$disp_name'","'$disc'","'$url'","$parent","$sublevel");
		$column=array("menu_id");
		$condition=array("$menu_id");
		$result = table_update("menu",$fields,$values,$column,$condition);
	}


	$message = base64_encode("Menu Saved Successfully.");
	header("Location: menu_list.php?msg=".$message);
	die();
}

if(isset($_GET['task']) && isset($_GET['eid']) && $_GET['task'] == 'edit' && intval($_GET['eid']) > 0 && EDITOR_ENABLED){
	$query = "SELECT * FROM menu WHERE menu_id=".intval($_GET['eid']);
	$result = mysql_query($query);
	$menu = mysql_fetch_object($result);
	
	include_once 'include/header.php';
	?>
	    <div class="menu_form_content">
	        <form action="<?php echo $_SERVER[PHP_SELF];?>" method="post" name="modul_add" onSubmit="return validation(this);">
	            <table cellpadding="5" cellspacing="5" border="0" width="100%">
					<!-- <tr>
						<td align="left">
							<a href="menu_list.php" class="menu_back_link" style="padding-right: 10px;">Back</a>
						</td>
					</tr> -->
	                	            
	                <tr>
	                    <td class="menu_lbl_td" width="35%"><label for="disp_name">Display Name</label></td>
	                    <td><input type="text" name="disp_name" id="disp_name" size="35" value="<?php echo filterdisplay($menu->display_name); ?>" required aria-required="true" placeholder="Menu Title"><span class="menu_req_field">&nbsp;</span></td>
	                </tr>
	              
	                <tr>
	                    <td class="menu_lbl_td" width="30%"><label for="description">Description</label></td>
	                    <td><input type="text" name="description" id="description" size="35" value="<?php echo filterdisplay($menu->description); ?>" required aria-required="true" placeholder="Menu Description"><span class="menu_req_field">&nbsp;</span></td>
	                </tr>
	                <tr>
	                	<td class="menu_lbl_td" width="30%" valign="top"><label for="disp_location">Display Location</label></td>
	                	<td>
		                	<?php
		                	$select_max = "SELECT max(sublevel) as maxlevel FROM menu";
		                	$result_max = mysql_query($select_max);
		
		                	if($result_max > 0)
		                	{
		                		$maxvalue = mysql_fetch_object($result_max);
		                		$maxlevel = $maxvalue->maxlevel;
		                	}
		                	else {
		                		$maxlevel = 0;
		                	}
		                	mysql_free_result($result_max);
		
		                	function getMenu($id, $pid, $current_id)
		                	{
		                		$query = "SELECT * FROM menu WHERE parent=". $id;
		                		$result = mysql_query($query);
		                		 
		                		if($result && mysql_num_rows($result) > 0)
		                		{
		                			while($row = mysql_fetch_object($result))
		                			{
		                				if(intval($row->menu_id) != $current_id)
		                				{
		                					$loc = intval($row->sublevel);
		                					$name = '';
		                					for($i=0; $i < $loc; $i++)
		                					{
		                						$name .=".&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		                					}
		
		                					$name .= "<sup>|_ </sup>&nbsp;" . filterdisplay($row->display_name);
		                					if(intval($row->menu_id) == $pid)
		                					$selected = 'selected';
		                					else
		                					$selected = '';
		
		                					echo "<option value=\"".filterdisplay($row->menu_id) ."_".filterdisplay($row->sublevel)."\" ".$selected."><span>". $name ." </span> </option>";
		                					getMenu(intval($row->menu_id), $pid, $current_id);
		                				}
		                			}
		                			mysql_free_result($result);
		                		}
		                	}
		                	?>
		                	<select name="disp_location" id="disp_location" size="15">
		                    <option value="0_0" selected>Top</option>
		                    	<?php  getMenu(0,$menu->parent, $menu->menu_id); ?>
		                    </select>
	                    </td>
	                </tr> 
	               
	               	<tr>
	                    <td class="menu_lbl_td" width="30%"><label for="url">URL</label></td>
	                    <td><input name="url" id="url" value="<?php echo filterdisplay($menu->url); ?>" size="35" required aria-required="true" placeholder="http://exmaple.org"><span class="menu_req_field">&nbsp;</span></td>
	                </tr>

					<!-- <tr>
						<td align="left">
							<a href="menu_list.php" class="menu_back_link" style="padding-right: 10px;">Back</a>
						</td>
					</tr> -->
	                
	              <tr>
	                	<td width="30%"></td>
	                    <td><input type="submit" class="menu_btn_save" value="Save" id="save" name="save" />
	                    	<input type="hidden" value="edit" id="action" name="action" />
	                        <input type="hidden" value="<?php echo filterdisplay($menu->menu_id); ?>" id="eid" name="eid" />
	                    	<input type="reset" class="menu_btn_save"/>
	                    </td>
	                </tr>
				</table>
	        </form>
		</div>
		
		<script type="text/javascript">
			function validation(menuForm){
				var arr = new Array("disp_name","description","disp_location","url");
				
				for(var i=0; i<arr.length; i++){
					var s = arr[i]; 
					var element = document.getElementById(s);
					
					if(element.value.length == 0){
						element.focus();
						element.style.border= "2px solid red";
						return false;
					}
					
				}
				return true;
			}	
	
			document.getElementById('disp_name').focus();
		</script>
		
	<?php include("include/footer.php"); 
	
} elseif(isset($_GET['task']) && $_GET['task'] == 'add' && EDITOR_ENABLED){
	include_once 'include/header.php';
	?>
	    <div class="menu_form_content">
	        <form action="<?php echo $_SERVER[PHP_SELF];?>" method="post" name="modul_add" onSubmit="return validation(this);">
	            <table cellpadding="5" cellspacing="5" border="0" width="100%">
					<!-- <tr>
						<td align="left">
							<a href="menu_list.php" class="menu_back_link" style="padding-right: 10px;">Back</a>
						</td>
					</tr> -->
	            
	                <tr>
	                    <td class="menu_lbl_td" width="35%"><label for="disp_name">Display Name</label></td>
	                    <td><input type="text" name="disp_name" id="disp_name" value="" size="35" required aria-required="true" placeholder="Menu Title"><span class="menu_req_field">&nbsp;</span></td>
	                </tr>
	              
	                <tr>
	                    <td class="menu_lbl_td" width="30%"><label for="description">Description</label></td>
	                    <td><input type="text" name="description" id="description" size="35" value="" required aria-required="true" placeholder="Menu Description"><span class="menu_req_field">&nbsp;</span></td>
	                </tr>
	                <tr>
	                	<td class="menu_lbl_td" width="30%" valign="top"><label for="disp_location">Display Location</label></td>
	                	<td>
		                     <?php
		                     $select_max = "SELECT max(sublevel) as maxlevel FROM menu";
		                     $result_max = mysql_query($select_max);
		
		                     if($result_max > 0)
		                     {
		                     	$maxvalue = mysql_fetch_object($result_max);
		                     	$maxlevel = $maxvalue->maxlevel;
		                     }
		                     else {
		                     	$maxlevel = 0;
		                     }
		                     mysql_free_result($result_max);
		
		                     function getMenu($pid)
		                     {
		                     	$query = "SELECT * FROM menu WHERE parent=". $pid;
		                     	$result = mysql_query($query);
		                     	$menu = array();
		                     	if($result && mysql_num_rows($result) > 0)
		                     	{
		                     		while($row = mysql_fetch_object($result))
		                     		{
		                     			$loc = intval($row->sublevel);
		                     			$name = '';
		                     			for($i=0; $i<$loc; $i++)
		                     			{
		                     				$name .=".&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		                     			}
		
		                     			$name .= "<sup>|_ </sup>&nbsp;" . filterdisplay($row->display_name);
		                     				
		                     			echo "<option value=".filterdisplay($row->menu_id) ."_".filterdisplay($row->sublevel)."><span>". $name ." </span> </option>";
		                     				
		                     			getMenu(intval($row->menu_id));
		                     		}
		                     		mysql_free_result($result);
		                     	}
		                     }
							?>
	                    <select name="disp_location" id="disp_location" size="15">
	                    <option value="0_0" selected>Top</option>
	                    	<?php  getMenu(0); ?>
	                    </select></td>
	                </tr> 
	               
	               	<tr>
	                    <td class="menu_lbl_td" width="30%"><label for="url">URL</label></td>
	                    <td><input name="url" id="url" value="#" size="35" required aria-required="true" placeholder="http://exmaple.org"><span class="menu_req_field">&nbsp;</span></td>
	                </tr>

					 <!-- <tr>
						<td align="left">
							<a href="menu_list.php" class="menu_back_link" style="padding-right: 10px;">Back</a>
						</td>
					</tr> -->
	                
	              <tr>
	                	<td width="30%"></td>
	                    <td><input type="submit" class="menu_btn_save" value="Save" id="save" name="save" />
	                    	<input type="hidden" value="add" id="action" name="action" />
	                    	<input type="reset" class="menu_btn_save"/>
	                    </td>
	                </tr>
				</table>
	        </form>
		</div>
	
		<script type="text/javascript" language="javascript">
			function validation(menuForm){
				var arr = new Array("disp_name","description","disp_location","url");
				
				for(var i=0; i<arr.length; i++){
					var s = arr[i]; 
					var element = document.getElementById(s);
					
					if(element.value.length == 0){
						element.focus();
						element.style.border= "2px solid red";
						return false;
					}
				}
				return true;
			}
	
			document.getElementById('disp_name').focus();	
		
		</script>
	
	<?php include("include/footer.php"); 		
} else {
	header("Location: ".INDEX_FILE);
	die();
}
?>
