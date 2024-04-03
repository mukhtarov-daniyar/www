<?php
	if(isset($_GET["search"]))
	{
		$pager->url = preg_replace("/\&p={$pager->page}/", "", $_SERVER["REQUEST_URI"]);
		$pager->url .= "&p=";
	}
	elseif ($CFG->pid == 100)
	{
		$pager->url = '/record'.preg_replace("/\?p={$pager->page}/", "", $_SERVER["REQUEST_URI"]);
		$pager->url .= "?p=";
	}
	elseif ($_GET["company"])
	{
		$pager->url = ''.preg_replace("/\?p={$pager->page}/", "", $_SERVER["REQUEST_URI"]);
		$pager->url .= "&p=";
	}
	else
	{
		$pager->url = preg_replace("/\?p={$pager->page}/", "", $_SERVER["REQUEST_URI"]);
		$pager->url .= "?p=";
	}


	$pager->pp = ($pager->pp <= 0 ? 1 : $pager->pp);
	$pager->pages = ceil($pager->cnt / $pager->pp);
	$pager->class = "pager";
?>

<div class="<?=$pager->class?>s">
<?
	if ($pager->pages > 1)
	{
?>
<?

		if ($pager->pages < 10)
			for ($pager->i=0; $pager->i<$pager->pages; $pager->i++)
			{
				$add1 = "";
				$add2 = "";
				if ($pager->i == $pager->page)
				{
				$add1 = "_act";
				}
?>
<a class='<?=$pager->class?>s<?=$add1?>' href="<?=($pager->url.$pager->i)?>" ><?=$pager->i+1?></a>
<?
			}
		else
		{
?>
<a href="<?=($pager->url.($pager->page>0 ? $pager->page-1 : 0))?>" class="back">« Сюда</a>
<?
			$pager->start = (($pager->page > 4) ? $pager->page-4 : 0);
			$pager->end   = (($pager->start+10 > $pager->pages) ? $pager->pages : $pager->start+10);
			if ($pager->start+10 > $pager->pages)
			{
				$pager->start = $pager->pages-10;
				$pager->end   = $pager->pages;
			}
			for ($pager->i=$pager->start; $pager->i<$pager->end; $pager->i++)
			{
				$add1 = "";
				$add2 = "";
				if ($pager->i == $pager->page)
				{
					$add1 = "_act";
				}
?>
<a class='<?=$pager->class?>s<?=$add1?>' href="<?=($pager->url.$pager->i)?>" ><?=$pager->i+1?></a>
<?
			}
?>
<a href="#">...</a>
<a class='<?=$pager->class?>s' href="<?=($pager->url.($pager->pages-1))?>" ><?=$pager->pages?></a>
<a  href="<?=($pager->url.($pager->page<$pager->pages-1 ? $pager->page+1 : $pager->pages-1))?>" class="next">Туда »</a>
<?
		}

	}
?>
</div>
