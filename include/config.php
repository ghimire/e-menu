<?php
// Database variable declaration

define('DB_HOST','localhost');
define('DB_NAME','INSERTDBNAMEHERE');
define('DB_USER','INSERTDBUSERHERE');
define('DB_PASS','INSERTDBPASSWORDHERE');

// Enable or disable editor
define('EDITOR_ENABLED', 1);

// Add or remove a menu for editor when editor is enabled
define('SHOW_EDITOR_MENU', 1);

// Define default file for redirection
define('INDEX_FILE', 'index.php');






// ******************************//
// Do Not Modify Below This Line //
// ******************************//

// current date and time
define('CURRENT_DATE',date("d-m-Y"));
define('CURRENT_TIME',date("H:i:s"));

$cn = mysql_connect(DB_HOST,DB_USER,DB_PASS);
mysql_select_db(DB_NAME,$cn);

if(!$cn){
	die("Connection Error".mysql_error());
}

// Insert Query Function
function table_insert($str_table_name, $str_field, $obj_values)
{
	$val_part="";
	$recresult="";
	$str_query = "insert into `$str_table_name` (";
	$int_count = 0;
	while ( $int_count < count($str_field) )
	{
		$str_query = $str_query .$str_field[$int_count];
		$val_part = $val_part .$obj_values[$int_count];
		if ( ($int_count + 1) != count($str_field) )
		{
			$val_part = $val_part . ", ";
			$str_query = $str_query. ", ";
		}
		$int_count++;
	}
	$str_query = $str_query.") values(".$val_part.")";
	$recresult=mysql_query($str_query);
	return mysql_affected_rows();
}

// Update Query Function
function table_update($str_table_name, $str_fields, $obj_values,$str_columnname,$obj_condition)
{
	$recresult="";
	$columns ="";
	$str_query ="";
	$str_query .= "UPDATE `$str_table_name` SET ";
	$int_count = 1;
	foreach ($str_fields as $int_key=>$int_value) {
		if ($int_count != count($str_fields) )
		{
			$str_query .= $str_fields[$int_key];
			$str_query .= "=".$obj_values[$int_key].",";
		}
		else
		{
			$str_query .= $str_fields[$int_key];
			$str_query .= "=".$obj_values[$int_key];
		}
		$int_count++;
	}

	if(count($str_columnname)>0){
		$str_query .= " WHERE " ;

		$int_count = 1;
		foreach ($str_columnname as $int_key=>$int_value) {
			if ($int_count != count($str_columnname) )
			{
				$str_query .= $str_columnname[$int_key];
				$str_query .= "=".$obj_condition[$int_key]." and ";
			}
			else
			{
				$str_query .= $str_columnname[$int_key];
				$str_query .= "=".$obj_condition[$int_key];
			}
			$int_count++;
		}
	}
	$recresult=mysql_query($str_query);
	return mysql_affected_rows();
}

function getTheme() {
    $themeresult = mysql_query("select theme from menutheme limit 1");
    $themerow = mysql_fetch_row($themeresult);
    return filterdisplay($themerow[0]);
}

function getMenuList($id)
{
	$query = "SELECT *, (select MAX(`order`) from menu where parent = ". $id . ") as maxorder FROM menu WHERE parent=". $id . " order by `order`";
	$result = mysql_query($query);

	if($result && mysql_num_rows($result) > 0)
	{
		$i=0;
		while($row = mysql_fetch_object($result))
		{
			$i++;
			$loc = filterdisplay($row->sublevel);
			$name = '';
			for($j=0; $j < $loc; $j++)
			{
				$name .=".&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			}
				
			$name .= "<sup>|_ </sup>&nbsp;" . filterdisplay($row->display_name);
			?>
			<tr class="d0">
				<td align="center">
					<a rel="facybox" href="menu_edit.php?task=edit&amp;eid=<?php echo filterdisplay($row->menu_id) ;?>"><img src="images/edit.png" title="Edit"></a>
				</td>
				<td align="left">
					<input style="width: 32px; height: 32px;" type="checkbox" name="menu_check_id[]" id="menu_check_id_<?php echo filterdisplay($row->menu_id) ;?>" value="<?php echo filterdisplay($row->menu_id) ;?>" />
				</td>
				<td align="left">
					<?php
					if($row->order != '1') {
					
						if( $row->order != $row->maxorder) { ?>
							<a href='<?php echo $_SERVER['PHP_SELF']."?dir=down&id=".filterdisplay($row->order)."&pid=".filterdisplay($row->parent);?>' style="padding-top: 2px;">
								<img src="images/down_arrow.png" border="0" height="32" width="32">
						<?php } else { ?>
								<img src="images/down_arrow0.png" border="0" height="32" width="32">
						<?php } ?>
						</a>&nbsp;&nbsp;
						<a href='<?php echo $_SERVER['PHP_SELF']."?dir=up&id=".filterdisplay($row->order)."&pid=".filterdisplay($row->parent);?>'>
							<img src="images/up_arrow.png" border="0" height="32" width="32" />
						</a>
					<?php
					} else {
					?>
						<a href='<?php echo $_SERVER['PHP_SELF']."?dir=down&id=".filterdisplay($row->order)."&pid=".filterdisplay($row->parent);?>' style="padding-top: 2px;">
							<img src="images/down_arrow.png" border="0" height="32" width="32">
						</a>&nbsp;&nbsp; 
						<img src="images/up_arrow0.png" border="0" height="32" width="32"> 
					<?php
					}
					?>
				</td>
				<td align="left"><?php echo $name; ?></td>
				<td align="left"><?php echo filterdisplay($row->description); ?></td>
				<td align="left"><a href="<?php echo filterdisplay($row->url); ?>" target="_blank"><?php echo filterdisplay($row->url); ?></a></td>
			</tr>
	<?php
	getMenuList($row->menu_id);
		}
		mysql_free_result($result);
	}
}

function filter($data){
	$data = trim(htmlentities(strip_tags($data)));
	if(get_magic_quotes_gpc())
		$data = stripslashes($data);
		
	$data = mysql_real_escape_string($data);
	return $data;
}

function filterdisplay($data){
    return htmlentities(strip_tags($data));
}

function process_post_variables(){
	foreach($_POST as $key => $value){
		if ($key != "menu_check_id")
			$_POST[$key] = filter($value);
		else
			$_POST[$key] = array_map('intval',$_POST[$key]);
	}
}

process_post_variables();

?>