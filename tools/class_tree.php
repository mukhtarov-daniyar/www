
class Tree
{

	var $table;
	var $parent;
	var $key;
	var $fields;
	var $orderby;
	var $where;

	function Tree($table, $key, $fields, $parent, $orderby, $where)
	{
		$this->table = $table;
		$this->key = $key;
		$this->fields = $fields;
		$this->parent = $parent;
		$this->orderby = $orderby;
		$this->where = $where;
	}

	function getCategoriesTree($parent=0, $includeHead=0)
	{
		GLOBAL $CFG;
		$tree = getTree($this->tree, $this->key, $this->fields, $this->parent, $this->orderby, $this->where);
		if ($parent>0)
		{
			$tree1 = array();
			for($i=0; $i<sizeof($tree) && $tree[$i]->id!=$parent; $i++);
			$lev = $tree[$i]->level;
			if ($includeHead)
				$tree1[] = $tree[$i];
			for($i++; $i<sizeof($tree) && $tree[$i]->level!=$lev; $i++)
				$tree1[] = $tree[$i];
			$tree = $tree1;
		}
		return $tree;
	}

}