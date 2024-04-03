<?php

if (!isset($_Cart_class_definition))
{

$_Cart_class_definition = "defined";


	$ORDER_STATUS->NEW = 1;
	$ORDER_STATUS->PACKED = 2;
	$ORDER_STATUS->PAID = 4;
	$ORDER_STATUS->SENT = 8;
	$ORDER_STATUS->DONE = 16;
	$ORDER_STATUS->DELETED = 32;
	$ORDER_STATUS->NAMES = array("Принят","Упакован","Оплачен","Отправлен","Выполнен", "Удален");
	$ORDER_STATUS->ACCESS = array(0, 0, 0, 0, 0, 1);

//	$PAYMENT_TYPES = array(2=>"Банковский перевод");
//	$DELIVERY_TYPES = array(3=>"Почта", 5=>"ЖелДорЭкспедиция");

	$PAYMENT_TYPES = array(1=>"Наложенный платеж", "Банковский перевод", "Кредитной картой", "WebMoney", "Яндекс-деньги");
	$DELIVERY_TYPES = array(3=>"Почта", 4=>"Автокарго", 5=>"ЖелДорЭкспедиция");

//	$OBL_TYPES = array("о"=>"области", "р"=>"республики", "к"=>"края", "а"=>"автономные округа");
	$OBL_TYPES = array("1"=>"области", "4"=>"республики", "2"=>"края", "3"=>"автономные округа");


class CartItem
{
	var $id = 0;
	var $art = "";
	var $name = "";
	var $cnt = 0;
	var $cost = 0;
	var $curr = "rur";
	var $weight = 0;
	var $cost_fob = 0;
	var $invoice = 0;
	var $discount = 0;
	var $discount_type = 0;

	function CartItem($id, $art, $name, $cnt, $cost, $weight, $curr)
	{
		$this->id	= $id;
		$this->art	= $art; 
		$this->name	= $name; 
		$this->cnt	= $cnt;
		$this->cost	= $cost;
		$this->curr	= $curr;
		$this->weight	= $weight;
		$this->cost_fob = 0;
		$this->invoice = 0;
		$this->discount = 0;
		$this->discount_type = 0;
//echo "<br>$id, $name, $cnt, $cost, $weight, $curr";
	}

	function setDiscount($discount, $discount_type)
	{
		$this->discount = $discount;
		$this->discount_type = $discount_type;
	}

}

class Cart
{
	var $discount = 0;
	var $cost = 0;
	var $discount_cost = 0;
	var $delivery_type = "";
	var $delivery_cost = 0;
	var $delivery_zone = "";
	var $total_cost = 0;
	var $items_count = 0;
	var $total_count = 0;
	var $items = null;
	var $weight = 0;
	var $session_cart_name = "cart_sys";
	var $session_cart_name2 = "cart_sys2";
	var $session_cart_name3 = "cart_sys3";
	var $session_cart_name4 = "cart_sys4";
	var $session_cart_name5 = "cart_sys5";

	function Cart()
	{
		session_register($this->session_cart_name);
		session_register($this->session_cart_name2);
		session_register($this->session_cart_name3);
		session_register($this->session_cart_name4);
		session_register($this->session_cart_name5);
		$this->init();
		$this->restore();

		$this->autoProcess();
	}

	function init()
	{
		$this->cost	= 0;
		$this->discount	= 0;
		$this->discount_cost = 0;
		$this->delivery_cost = 0;
		$this->delivery_zone = 0;
		$this->total_cost	= 0;
		$this->items	= array();
		$this->items_count	= 0;
		$this->total_count	= 0;
		$this->weight = 0;
	}

	function clear()
	{
		$this->init();
		$_SESSION[$this->session_cart_name] = $this->items;
		session_unregister($this->session_cart_name);
		session_register($this->session_cart_name);

		session_unregister($this->session_cart_name2);
		session_register($this->session_cart_name2);
	}

	function addItem($id, $art, $name, $cnt, $cost, $weight, $discount=0, $discount_type=0, $currency="rur")
	{
		if ($cnt <= 0)
			return;

		if (is_object($this->items[$id]))
		{
			$this->items[$id]->cnt += $cnt;
		}
		else
		{
//die("-".$cost);
			$item = new CartItem($id, $art, $name, $cnt, $cost, $weight, $currency);
			$this->items[$id] = $item;
			$this->items[$id]->setDiscount($discount, $discount_type);
		}
		$this->recalc();
	}

	function recalc()
	{
		GLOBAL $CFG;

		$this->cost	= 0;
		$this->discount_cost	= 0;
		$this->total_cost	= 0;
		$this->items_count	= 0;
		$this->total_count	= 0;
		$this->weight = 0;
		$tmp = $this->items;
		$T = ($_SESSION["SYS"]->USER_ACL ? "2" : "");
		while(list($key, $obj) = each($tmp))
		{
			if (!is_object($obj))
				continue;

//			$items1 = get_products_from_invoices("PR.id='$obj->id'");
//			$items1[0] = $obj;
			$items1 = getSQLArrayO("SELECT valute, weight, cost{$T} AS cost FROM {$CFG->DB_Prefix}prods WHERE id='{$obj->id}'");
			$obj->weight = $items1[0]->weight;
			$obj->cost = $items1[0]->cost;
			$obj->curr = $items1[0]->valute;
			$this->items[$obj->id]->cost = $items1[0]->cost;

			$this->total_count += $obj->cnt;

			$obj_cost = $obj->cnt*$obj->cost;
			if ($obj->curr != "rur")
				$obj_cost = 1*$this->convCurr($obj_cost, $obj->curr);

			$this->cost += $obj_cost;

			$price = 1 * $obj->cost;
			if ($obj->discount > 0)
			{
				if ($obj->discount_type==0)
					$price = $price - $obj->discount;
				else
					$price = 1 * trim(sprintf("%10.2f", ($price * (100-$obj->discount))/100));
			}
			$this->items[$obj->id]->cost = $price;
			$price = 1 * sprintf("%10.2f", $price - ($price/100)*$this->discount);

			if ($obj->curr != "rur")
				$price = 1*$this->convCurr($price, $obj->curr);
			$this->discount_cost += $obj->cnt*$price;

			$this->weight += $obj->cnt*$obj->weight;
			$this->items_count++;
		}

		$l = explode("|", $this->delivery_zone);
/*
		$this->delivery_type = 3;
		if ($this->discount_cost > 10000)
		{
			if ($l[0] == 27)	// if Novosibirskaja oblast'
				$this->delivery_type = 4;
			else
				$this->delivery_type = 5;
		}
*/
		if ($this->ONLINE_PROCESSING)
		{
			$d = new Delivery($this->delivery_type);
			$this->delivery_cost = $d->getCost($this->weight, $l[0], $l[1]);
		}
		else
			$this->delivery_cost = 0;

		$this->total_cost = $this->discount_cost + $this->delivery_cost;

		session_unregister($this->session_cart_name);
		session_register($this->session_cart_name);
		$_SESSION[$this->session_cart_name] = $this->items;
	}

	function restore()
	{
		$this->discount = $_SESSION[$this->session_cart_name2];
		$this->delivery_cost = $_SESSION[$this->session_cart_name3];
		$this->delivery_zone = $_SESSION[$this->session_cart_name4];
		$this->delivery_type = $_SESSION[$this->session_cart_name5];

		if (!$_SESSION[$this->session_cart_name])
			return;

		$tmp = $_SESSION[$this->session_cart_name];
		while(list($key, $obj1) = each($tmp))
		{
			$obj = array();
			while(list($k1, $o1) = @each($obj1))
				$obj[$k1] = $o1;
			if ($obj["cnt"])
			{
				$this->addItem($obj["id"], $obj["art"], $obj["name"], $obj["cnt"], $obj["cost"], $obj["weight"], $obj["discount"], $obj["discount_type"], $obj["curr"]);
			}
		}
	}

	function removeItem($id)
	{
		if (!($obj = $this->items[$id]))
			return;

		unset($this->items[$id]);

		$this->recalc();
	}

	function changeItem($id, $cnt)
	{
		if (!($obj = $this->items[$id]))
			return;

		$this->removeItem($id);
		$this->addItem($obj->id, $obj->art, $obj->name, $cnt, $obj->cost, $obj->weight, $obj->discount, $obj->discount_type, $obj->curr);
	}

	function setDiscount($discount)
	{
		$this->discount = $discount;
		$_SESSION[$this->session_cart_name2] = $this->discount;
		$this->recalc();
	}

	function setDeliveryType($delivery_type, $delivery_zone="")
	{
		$this->delivery_zone = $delivery_zone;
		$this->delivery_type = $delivery_type;
		$_SESSION[$this->session_cart_name4] = $this->delivery_zone;
		$_SESSION[$this->session_cart_name5] = $this->delivery_type;
		$this->recalc();
	}

	function getDeliveryName()
	{
		$d = new Delivery($this->delivery_type);
		return $d->getName();
	}



	function pay()
	{
		GLOBAL $CFG;

				if (!$CFG->USER->is_loggedIn())
				{
					$data = stripslashesarray($_POST);
					$s  = "";
					$s .= "<table>\n";
					$s .= "<tr><td align='right'>Наименование Вашей организации:  </td><td>{$data["company"]}</td></tr>\n";
					$s .= "<tr><td align='right'>Город, в котором находиться ваша организация:  </td><td>{$data["city"]}</td></tr>\n";
					$s .= "<tr><td align='right'>Контактное лицо:  </td><td>{$data["name"]}</td></tr>\n";
					$s .= "<tr><td align='right'>Телефон:  </td><td>{$data["phone"]}</td></tr>\n";
					$s .= "<tr><td align='right'>E-mail:  </td><td>{$data["email"]}</td></tr>\n";
					$s .= "</table>\n";

				}
				else
				{
					$data = $CFG->USER->getUserInfo($CFG->USER->USER_ID);

					$s  = "";
					$s .= "<table>\n";
					$s .= "<tr><td align='right'>Логин:  </td><td>{$data["login"]}</td></tr>\n";
					$s .= "<tr><td align='right'>Наименование Вашей организации:  </td><td>{$data["company"]}</td></tr>\n";
					$s .= "<tr><td align='right'>Профиль деятельности вашей организации: </td><td>{$data["buss"]}</td></tr>\n";
					$s .= "<tr><td align='right'>Город, в котором находиться ваша организация:  </td><td>{$data["city"]}</td></tr>\n";
					$s .= "<tr><td align='right'>Ваша должность:  </td><td>{$data["obl"]}</td></tr>\n";
					$s .= "<tr><td align='right'>Ваша Фамилия, Имя, Отчество (полностью):  </td><td>{$data["name"]}</td></tr>\n";
					$s .= "<tr><td align='right'>Какие типы продукции и услуг Вас интересуют:  </td><td>{$data["avatar"]}</td></tr>\n";
					$s .= "<tr><td align='right'>Ваши контактные телефоны:</td><td>{$data["o"]}</td></tr>\n";
					$s .= "<tr><td align='right'>Городской:</td><td>{$data["phone"]}</td></tr>\n";
					$s .= "<tr><td align='right'>Мобильный: </td><td>{$data["fax"]}</td></tr>\n";
					$s .= "<tr><td align='right'>Ваш E-mail: </td><td>{$data["email"]}</td></tr>\n";
					$s .= "</table>\n";

				}


//---------------------------------



$res = "";


	$res .= "<table width='100%' cellpadding='4' cellspacing='0' class='pan1'>\n";
$res .= "<tr>\n";
$res .= "	<td class='cart2' valign='top'><b>Артикул</b></td>\n";
$res .= "	<td class='cart2' valign='top'><b>Название</b></td>\n";
$res .= "	<td class='cart2' valign='top'><b>Количество</b></td>\n";
$res .= "	<td class='cart2' valign='top'><b>Цена, р.</b></td>\n";
$res .= "	<td class='cart2' valign='top'><b>Сумма, р.</b></td>\n";
$res .= "</tr>\n";

	$CFG->colorBG3 = "#FFFFFF";
	$CFG->colorBG1 = "#EEEEEE";
	$bg = $CFG->colorBG3;
	$tmp = $this->items;
	$total = 0;
	while(list($key, $obj) = each($tmp))
	{
		$bg = ($bg==$CFG->colorBG3 ? $bg = $CFG->colorBG1 : $CFG->colorBG3);

		$s = $obj->cnt * $obj->cost;

$res .= "<tr bgcolor='{$bg}'>\n";
$res .= "	<td class='cart2' valign='top'>{$obj->art}</td>\n";
$res .= "	<td class='cart2' valign='top'>{$obj->name}</td>\n";
$res .= "	<td class='cart2' valign='top'>{$obj->cnt}</td>\n";

	if ($CFG->CATALOG->USE_COST)
	{
		$str1 = $obj->cost;
		$str2 = $s;
		if ($obj->curr != "rur")
		{
			$str1 = $this->convCurr($obj->cost, $obj->curr)." <sub>(".$obj->cost." ".$this->showCurr($obj->curr).")</sub>";
			$str2 = $this->convCurr($s, $obj->curr)." <sub>(".$s." ".$this->showCurr($obj->curr).")</sub>";
			$s = 1*$this->convCurr($obj->cost, $obj->curr);
		}

$res .= "	<td class='cart2' valign='top'>{$str1}</td>\n";
$res .= "	<td class='cart2' valign='top'>{$str2}</td>\n";

	}

$res .= "</tr>\n";

		$total += $s;
	}

$res .= "<tr bgcolorrr='#FFFFFF'>\n";
$res .= "	<td class='cart2' colspan='".($span+1)."'><hr size='1' /></td>\n";
$res .= "</tr>\n";

	if ($CFG->CATALOG->USE_COST)
	{

	if ($this->discount > 0 || $this->ONLINE_PROCESSING == 1)
	{
$res .= "<tr bgcolorrr='#FFFFFF'>\n";
$res .= "	<td class='cart2' valign='top' colspan='{$span}'>Всего:</td>\n";
$res .= "	<td class='cart2' valign='top'>{$total}</td>\n";
$res .= "</tr>\n";
	}

	if ($this->discount > 0) 
	{
$res .= "<tr bgcolorrr='#FFFFFF'>\n";
$res .= "	<td class='cart2' valign='top' colspan='{$span}'>Всего (со скидкой {$this->discount}%):</td>\n";
$res .= "	<td class='cart2' valign='top'>${$this->discount_cost}</td>\n";
$res .= "</tr>\n";
	}

	if ($this->ONLINE_PROCESSING == 1)
	{
$res .= "<tr bgcolorrr='#FFFFFF'>\n";
$res .= "	<td class='cart2' valign='top' colspan='{$span}'>Доставка (".$this->getDeliveryName()."):</td>\n";
$res .= "	<td class='cart2' valign='top'>".($this->delivery_cost>0 ? $this->delivery_cost : "Неизвестно")."</td>\n";
$res .= "</tr>\n";
	}
$res .= "<tr bgcolorrr='#FFFFFF'>\n";
$res .= "	<td class='cart2' valign='top' colspan='{$span}'>ИТОГО:</td>\n";
$res .= "	<td class='cart2' valign='top'>{$this->total_cost}</td>\n";
$res .= "</tr>\n";

	}
$res .= "</table>\n";



	$sql  = "INSERT INTO {$CFG->DB_Prefix}site_visits_orders (cdate, user_id, session_id, q_id, cost, ctype) VALUES (";
	$sql .= "'".sqlDateNow()."',";
	$sql .= "'{$CFG->USER->USER_ID}',";
	$sql .= "'{$_REQUEST["PHPSESSID"]}',";
	$sql .= "'{$_COOKIE["SB_visited"]}',";
	$sql .= "'{$this->total_cost}', '1')"; 	// 1 - zakaz,  0 - zajavka
	$CFG->DB->query($sql);


			$oPage = getPageInfo(112);
			$to = $oPage->aOptions["email"];

			$m = new ASMail();

			$m->setTo($to);
//			$m->setFrom("mailFrom@mail.ru");
			$m->setContentCharset("windows-1251");
			$m->setSubject("Заказ с сайта.");
			$m->addHtml($s."<br>\n<br>\n".$res);

			$m->send();




			$m = new ASMail();

			$m->setTo($to2);
			$m->setFrom($to);
			$m->setContentCharset("windows-1251");
			$m->setSubject("Ваш заказ на сайте {$_SERVER["SERVER_NAME"]}");
			$m->addHtml($res);

			$m->send();




//die($res."<br><br>".$to );


//-----------------------------------
	}




	function autoProcess()
	{
		GLOBAL $CFG;

		if ($_POST["cart_act"] == "addItem")
		{
			$addId = 1 * $_POST["itemID"];
			$items = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}prods WHERE id='{$addId}'");
			if (sizeof($items) > 0)
				$this->addItem($addId, $items[0]->article, $items[0]->name, 1*$_POST["cnt{$addId}"], $items[0]->cost, $items[0]->weight, 0, 0, $items[0]->valute);
			redirect($CFG->REQUEST_URI);
		}

		if ($_POST["cart_act"] == "delItem")
		{
			$this->changeItem(1*$_POST["itemID"], 0);
			redirect($CFG->REQUEST_URI);
		}



		if ($_POST["do_recalc"] != "" || $_POST["do_recalc_go"] != "")
		{
			$tmp = $this->items;
			$MAIN_MESSAGE = "";
			while(list($key, $obj) = each($tmp))
			{
		//die("$key - $obj");
				$items = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}prods WHERE id='{$key}'");
        		$new_qty = $_POST["cnt_$key"];
				if ($new_qty < 1)
					$new_qty = 0;
					$this->changeItem($key, $new_qty);
		//			if ($GLOBALS["add_discount_$key"] > 0)
					{
		//				$this->items[$key]->setDiscount($GLOBALS["add_discount_$key"], $GLOBALS["add_discount_type_$key"]);
					}
		
				}
				if ($_POST["do_recalc"] != "" || $MAIN_MESSAGE != "")
					redirect($CFG->REQUEST_URI);
				else
				{
					if ($_POST["login"] != "")
						$CFG->USER->login($_POST["login"], $_POST["pass"]);
					if (!$CFG->USER->is_loggedIn())
						redirect("index.php?sys=order_reg");
					else
						$_POST["cart_act"] = "pay";

//					redirect("index.php?sys=order_delivery");
				}
        }
		
		
		
		if ($_POST["do_clear"] != "")
		{
			$this->clear();
			redirect($CFG->REQUEST_URI);
		}
			
		
		
		if ($_POST["cart_act"] == "pay")
		{
		 	$this->pay();
			redirect("index.php?sys=order_success");
		}

		if ($_POST["cart_act"] == "pay----")
		{

				if (!$CFG->USER->is_loggedIn())
				{
					$data = $_POST;
				}
				else
				{
					$data = $CFG->USER->getUserInfo($CFG->USER->USER_ID);
				}


//---------------------------------



$res = "";


	$res .= "<table width='100%' cellpadding='4' cellspacing='0' class='pan1'>\n";
$res .= "<tr>\n";
$res .= "	<td class='cart2' valign='top'><b>Артикул</b></td>\n";
$res .= "	<td class='cart2' valign='top'><b>Название</b></td>\n";
$res .= "	<td class='cart2' valign='top'><b>Количество</b></td>\n";
$res .= "	<td class='cart2' valign='top'><b>Цена, р.</b></td>\n";
$res .= "	<td class='cart2' valign='top'><b>Сумма, р.</b></td>\n";
$res .= "</tr>\n";

	$CFG->colorBG3 = "#FFFFFF";
	$CFG->colorBG1 = "#EEEEEE";
	$bg = $CFG->colorBG3;
	$tmp = $this->items;
	$total = 0;
	while(list($key, $obj) = each($tmp))
	{
		$bg = ($bg==$CFG->colorBG3 ? $bg = $CFG->colorBG1 : $CFG->colorBG3);

		$s = $obj->cnt * $obj->cost;

$res .= "<tr bgcolor='{$bg}'>\n";
$res .= "	<td class='cart2' valign='top'>{$obj->art}</td>\n";
$res .= "	<td class='cart2' valign='top'>{$obj->name}</td>\n";
$res .= "	<td class='cart2' valign='top'>{$obj->cnt}</td>\n";

	if ($CFG->CATALOG->USE_COST)
	{
		$str1 = $obj->cost;
		$str2 = $s;
		if ($obj->curr != "rur")
		{
			$str1 = $this->convCurr($obj->cost, $obj->curr)." <sub>(".$obj->cost." ".$this->showCurr($obj->curr).")</sub>";
			$str2 = $this->convCurr($s, $obj->curr)." <sub>(".$s." ".$this->showCurr($obj->curr).")</sub>";
			$s = 1*$this->convCurr($obj->cost, $obj->curr);
		}

$res .= "	<td class='cart2' valign='top'>{$str1}</td>\n";
$res .= "	<td class='cart2' valign='top'>{$str2}</td>\n";

	}

$res .= "</tr>\n";

		$total += $s;
	}

$res .= "<tr bgcolorrr='#FFFFFF'>\n";
$res .= "	<td class='cart2' colspan='".($span+1)."'><hr size='1' /></td>\n";
$res .= "</tr>\n";

	if ($CFG->CATALOG->USE_COST)
	{

	if ($this->discount > 0 || $this->ONLINE_PROCESSING == 1)
	{
$res .= "<tr bgcolorrr='#FFFFFF'>\n";
$res .= "	<td class='cart2' valign='top' colspan='{$span}'>Всего:</td>\n";
$res .= "	<td class='cart2' valign='top'>{$total}</td>\n";
$res .= "</tr>\n";
	}

	if ($this->discount > 0) 
	{
$res .= "<tr bgcolorrr='#FFFFFF'>\n";
$res .= "	<td class='cart2' valign='top' colspan='{$span}'>Всего (со скидкой {$this->discount}%):</td>\n";
$res .= "	<td class='cart2' valign='top'>${$this->discount_cost}</td>\n";
$res .= "</tr>\n";
	}

	if ($this->ONLINE_PROCESSING == 1)
	{
$res .= "<tr bgcolorrr='#FFFFFF'>\n";
$res .= "	<td class='cart2' valign='top' colspan='{$span}'>Доставка (".$this->getDeliveryName()."):</td>\n";
$res .= "	<td class='cart2' valign='top'>".($this->delivery_cost>0 ? $this->delivery_cost : "Неизвестно")."</td>\n";
$res .= "</tr>\n";
	}
$res .= "<tr bgcolorrr='#FFFFFF'>\n";
$res .= "	<td class='cart2' valign='top' colspan='{$span}'>ИТОГО:</td>\n";
$res .= "	<td class='cart2' valign='top'>{$this->total_cost}</td>\n";
$res .= "</tr>\n";

	}
$res .= "</table>\n";









die($res);


//-----------------------------------




        		$ptype = 1 * $_POST["ptype"];
		
				$oid = $this->saveOrder($ptype);
		
				$blank_orderNum = $oid;
				$blank_summ = $this->total_cost;	// * $RUR2USD;
				$blank_userName = $CFG->USER->USER_NAME."\n".$SES_DELIVERY_ADDR;
		
				$o = getSQLRowO("SELECT * FROM {$CFG->DB_Prefix}cfg WHERE mnemo='order'");
				$email = $o->emailto;
				$esubj = $o->esubj;
				$ebody = $o->ebody;
				$ebody = ereg_replace("\[id\]", $oid, $ebody);
				@mail($email, $esubj, $ebody);
        
				$_SESSION["last_order"] = $oid;
				if ($ptype == 1)
					redirect("index.php?sys=order_success");
				if ($ptype == 2)
					redirect("index.php?sys=order_success&blank=1");
				if ($ptype == 3)
					redirect("index.php?sys=pay_assist");
				if ($ptype == 4)
					redirect("index.php?sys=pay_webmoney");
				if ($ptype == 5)
					redirect("index.php?sys=pay_yandex");
				redirect("index.php?sys=order_success");
		}
        


		if ($_POST["goto_pay"] != "")
		{
			if ($_POST["d2"]=="")
				$_POST["d2"] = trim($_POST["d2_2"]);
			$d11 = getSQLField("SELECT title FROM {$CFG->DB_Prefix}post_rajons WHERE id='{$_POST["d1"]}'");
			$_SESSION["SES_DELIVERY_ADDR"] = "Индекс: {$_POST["d0"]}\nРегион: {$d11}\nГород: {$_POST["d2"]}\nАдрес: {$_POST["d3"]}\nТелефон: {$_POST["d4"]}\nE-mail: {$_POST["email"]}\nФ.И.О.: {$_POST["fio"]}\n";
			$_SESSION["SES_DELIVERY_INFO"] = $_POST["user_info"];
			$_SESSION["SES_DELIVERY_PAGE"] = "index.php?sys=order_payment";
			$_SESSION["SES_DELIVERY_RAJON"] = $_POST["d1"];
			$_SESSION["SES_DELIVERY_RAJON_NAME"] = $d11;
			$_SESSION["SES_DELIVERY_CITY_NAME"] = trim($_POST["d2"]);
			$_SESSION["SES_DELIVERY_RAJON"] = $_POST["d1"];
			$_SESSION["SES_DELIVERY_CITY"] = trim($_POST["d2"]);
        //	$this->setDeliveryType($_SESSION["SES_DELIVERY_TYPE"], "{$_SESSION["SES_DELIVERY_RAJON"]}|{$_SESSION["SES_DELIVERY_CITY"]}");

			if($this->ONLINE_PROCESSING == 1)
				redirect("index.php?sys=order_delivery_type");
			else
				redirect("index.php?sys=order_payment");
		}
		
		if ($_POST["cart_act"] == "cart_setDeliveryType")
		{
				$dtype = 1 * $_POST["dtype"];
				$_SESSION["SES_DELIVERY_TYPE"] = $dtype;
				$this->setDeliveryType($_SESSION["SES_DELIVERY_TYPE"], "{$_SESSION["SES_DELIVERY_RAJON"]}|{$_SESSION["SES_DELIVERY_CITY"]}");
				redirect("index.php?sys=order_payment");
		}
	}



	function saveOrder($ptype)
	{
		GLOBAL $CFG, $SYS, $AFF, $CART, $db, $ORDER_STATUS;

		$remote = $GLOBALS["HTTP_X_FORWARDED_FOR"];
		if ($remote == "" || $remote == "unknown")
			$remote = $GLOBALS["REMOTE_ADDR"]; 

		$sql  = "INSERT INTO {$CFG->DB_Prefix}orders (user_id, cdate, ip, aff, pay_type, deliv_type, status, user_info, deliv_addr, total_cost, deliv_cost, deliv_zone, discount) VALUES (";
		$sql .= "'".$CFG->USER->USER_ID."', ";
		$sql .= "'".sqlDateNow()."', ";
		$sql .= "'".$remote."', ";
		$sql .= "'".$AFF->AUID."', ";
		$sql .= "'".$ptype."', ";
		$sql .= "'".$this->delivery_type."', ";
		$sql .= "'".$ORDER_STATUS->NEW."', ";
		$sql .= "'".$_SESSION["SES_DELIVERY_INFO"]."', ";
		$sql .= "'".$_SESSION["SES_DELIVERY_ADDR"]."', ";
		$sql .= "'".$this->discount_cost."', ";
		$sql .= "'".$this->delivery_cost."', ";
		$sql .= "'".$this->delivery_zone."', ";
		$sql .= "'".$this->discount."') ";
//echo("<br>".$sql);
		$db->query($sql);
		$oid = $db->last_id();

		$tmp = $this->items;
		while(list($key, $obj) = each($tmp))
		{
			$sql  = "INSERT INTO {$CFG->DB_Prefix}order_items (order_id, product_id, price, qty, discount, price_fob, invoice_id) VALUES (";
			$sql .= "'$oid', ";
			$sql .= "'$obj->id', ";
			$sql .= "'$obj->cost', ";
			$sql .= "'$obj->cnt', ";
			$sql .= "'$this->discount', ";
			$sql .= "'$obj->cost_fob', ";
			$sql .= "'$obj->invoice') ";
//echo("<br>".$sql);
			$db->query($sql);
//			decProductCount($obj->id, $obj->cnt);
		}		
//die();

		return $oid;
	}



	function showCurr($str)
	{
		if ($str == "rur")	return "руб.";
		if ($str == "usd")	return "USD";
		if ($str == "eur")	return "EUR";
		return "";
	}

	function convCurr($cost, $curr)
	{
		$r = 1;
		if ($curr == "rur")	$r = 1;
		if ($curr == "usd")	$r = 23.7;
		if ($curr == "eur")	$r = 36.4;
		return trim(sprintf("%10.2f", $cost*$r));
	}



} // end of the Cart class


} // end of the definition checking

?>