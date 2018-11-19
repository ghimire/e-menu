<link rel="stylesheet" href="css/menu.css">
<ul id="<?php echo getTheme(); ?>" class="menustyle">
<?php 
function getMenuDisplay($id)
{
	$query = "SELECT * FROM menu WHERE parent=".$id." order by `order`";
	$result = mysql_query($query);

	if($result && mysql_num_rows($result) > 0)
	{
		if($id == 0)
			echo "\n";
		else
			echo "<ul>";

		while($row = mysql_fetch_object($result))
		{
			echo "<li><a href='".filterdisplay($row->url)."'>".filterdisplay($row->display_name)."</a>\n";
			getMenuDisplay(intval($row->menu_id));
			echo "</li>\n";
		}
		
		if($id == 0)
			echo "\n";
		else
			echo "</ul>";		
		
			mysql_free_result($result);
	}
}
	getMenuDisplay(0);
?>
	<?php if (EDITOR_ENABLED && SHOW_EDITOR_MENU) { ?>
     <li style="float: right;"><a style="color: #ff8000;" href="menu_list.php">Menu Editor</a></li>
    <?php } ?>
</ul>