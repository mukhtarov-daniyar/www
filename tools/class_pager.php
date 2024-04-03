<?
class Pager
{
	var $cnt;
	var $pp;
	var $page;
	var $pages;
	var $url;
	var $limit;
	var $class;



	function Pager($cnt, $pp, $class="pager")
	{
		if ($cnt <= 0)
			$cnt = 1;
		if ($pp <= 0)
			$pp = $cnt;
		$this->pp = $pp;
		$this->cnt = $cnt;
		$this->pages = ceil($cnt / $pp);

		if (isset($_GET["p"]) || isset($_POST["p"]))
		{
			$this->page = 1 * getGP("p");
			$_SESSION["pager"][md5($_SERVER["REQUEST_URI"])] = $this->page;
		}
		else
			$this->page = 1 * $_SESSION["pager"][md5($_SERVER["REQUEST_URI"])];

		if ($this->page >= $this->pages)
			$this->page = 0;
		$this->start = $this->page * $this->pp;
		$this->limit = " LIMIT {$this->start}, {$this->pp}";
		$this->class = $class;
	}
}
