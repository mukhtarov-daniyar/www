<?

//error_reporting(65535);
	include_once("../../_properties.php");

	$CFG->FORM = new Form();

	if ($_POST["form_action"] != "")
	{
        	$cnt = $CFG->FORM->loadForm($_POST["page"]);

		$CFG->FORM->saveData($_POST);

                $_SESSION["message_done"] = 1;



		$cntData = $CFG->FORM->loadData("DESC", 1);


		$oPage = getPageInfo($_POST["page"]);

		$email = $oPage->aOptions["email"];

		$m = new ASMail();

		$m->setTo($email);
		$m->setFrom($email);
		$m->setSubject($oPage->name);


		$r = "<table border=\"1\">\r\n";
		for ($i=0; $i<$cnt; $i++)
		{
			$data = $CFG->FORM->getData(0, $i);

			$r .= "<tr>\r\n";
			$r .= "<td><b>".$CFG->FORM->getLabel($i)."</b></td>\r\n";
			$r .= "<td>".nl2br(ecrane($data))."</td>\r\n";
			$r .= "</tr>\r\n";

			if ($CFG->FORM->getType($i) == 12)
				$m->addFile("./{$CFG->DOC_PATH}formfiles/{$_PAGE_ID}/{$data}", $data);

		}
		$r .= "</table>\r\n";


		$m->addHtml($r);

		$res = $m->send(1);


//mail("aster54@mail.ru", "dffrfr", "body");

//die($res."=[{$oPage->name} - {$email}]=".$r);
		redirect($_SERVER["HTTP_REFERER"]);
	}


	include("_header.php");

	showHeader($oPage->name);

	$cat_id = 1 * $_GET["cid"];


//	showHdr5( "<a href='index.php?page={$pid}'><b>".hs($oPage->name)."</b></a> / ".getFullGalleryPath($cat_id) );
	
	for ($i=0; $i<sizeof($aBlocks); $i++)
	{
//		showGenericBlock(parse_images($aBlocks[$i]), $aOptions);
		showGenericBlock($aBlocks[$i], $aOptions);
	}


	$cnt = $CFG->FORM->loadForm($pid);

/*
?>
	<table border="1">
	<tr>
<?
		for ($i=0; $i<$cnt; $i++)
		{
?>
		<td><?=$CFG->FORM->getLabel($i)?></td>
<?
		}
?>
	</tr>
<?

	$cntData = $CFG->FORM->loadData();

	for ($d=0; $d<$cntData; $d++)
	{
		echo "\t<tr>\n";
		for ($i=0; $i<$cnt; $i++)
		{
?>
		<td><?=ecrane($CFG->FORM->getData($d, $i))?></td>
<?
		}
		echo "\t</tr>\n";
	}


?>
	</table>
<?
*/

	if ($cnt > 0)
	{
?>
<script type="text/javascript">
<!--
function test_form2()
{
<?=$CFG->FORM->writeTestScript();?>
}
function test_form()
{
	if (!test_form2())
	{
		alert("Заполните все обязательные поля.");
		return false;
	}
	else
		return true;
}
-->
</script>

	<form method="post" action="/modules/form/form.php" enctype="multipart/form-data" onsubmit="return test_form()">
	<input type="hidden" name="page" value="<?=$pid?>" />
	<input type="hidden" name="p" value="<?=$CFG->FORM->page?>" />
	<input type="hidden" name="form_action" value="save" />
	<table align="center" class="form_table">
				<tr>
					<td colspan="2" align='center'><b><?=($_SESSION["message_done"] ? "Ваш заказ принят." : "")?></b>&nbsp;</td>
				</tr>
<?
		for ($i=0; $i<$cnt; $i++)
		{
?>
		<tr style=''>
<?
			if ($CFG->FORM->getType($i) <> 10  &&  $CFG->FORM->getType($i) <> 11)
			{
?>
			<td class="form_title"><?=$CFG->FORM->getLabel($i)?>: </td>
			<td class="form_enter"><?=$CFG->FORM->getInput($i)?></td>
<?
			}
			else
			{
?>
			<td colspan="2"><?=$CFG->FORM->getInput($i)?></td>
<?
			}
?>
		</tr>
<?
		}
?>

		<tr>
			<td align='right'></td>
			<td><br /><input type="submit" value="Отправить" /></td>
		</tr>
	</table>
	</form>
<?
		$_SESSION["message_done"] = 0;
	}
	else
		echo "Информациия для данного раздела готовится. Извините за временные неудобства.";


//	include("_pager.php");


include("_footer.php");

?>