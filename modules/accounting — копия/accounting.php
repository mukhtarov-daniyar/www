<?

$ACCOUNT = new Acon();


 		switch($CFG->_GET_PARAMS[0]) 
		{
		
			case 'record' :

				if($CFG->USER->USER_ID)
					{
						$cdate = sqlDateNow();
						$time = time();
						
						if($_COOKIE["company"] > 0)
							$director = $_COOKIE["company"];
						else
							$director = $CFG->USER->USER_DIRECTOR_ID;
							
						$sql = "INSERT INTO {$CFG->DB_Prefix}money_accounting (director_id, author_id, user_id, type_id, cat_id, page_id, time, cdate, price, text, attachment, visible) VALUES ({$director}, {$CFG->USER->USER_ID}, '{$_POST[user]}', '{$_POST[type]}', '{$_POST[cat]}', '{$CFG->oPageInfo->id}', '{$time}', '{$cdate}', '{$_POST[price]}', '{$_POST[text]}', '{$_POST[attachment]}', 1)";
						
						if($CFG->DB->query($sql))
						{
							$o = getSQLRowO("SELECT price,time FROM {$CFG->DB_Prefix}money_accounting WHERE visible='1' ORDER BY id DESC limit 1 ");
			
							$status = json_encode(array('status' => 1, 'text' => "Сумма <strong>".$o->price." ".$CFG->USER->USER_CURRENCY."</strong> успешно внесена.<br> Код операции: <strong>".$o->time."</strong>"));
							echo $status; exit;	
						}
						else
						{
							$status = json_encode(array('status' => 0, 'text' => "Произошла ошибка системы, пожалуйста обновите страницу."));
							echo $status; exit;
						}
						
					}
					else
					{
						$status = json_encode(array('status' => 0, 'text' => "Произошла ошибка системы, пожалуйста обновите страницу."));
						echo $status; exit;
					}

				
			break;
			
			
			case 'record_minus' :
				if($CFG->USER->USER_ID)
					{

						$cdate = sqlDateNow();
						$time = time();
						
						if($_COOKIE["company"] > 0)
							$director = $_COOKIE["company"];
						else
							$director = $CFG->USER->USER_DIRECTOR_ID;
							
						$sql = "INSERT INTO {$CFG->DB_Prefix}money_accounting (director_id, author_id, user_id, type_id, cat_id, page_id, time, cdate, price, text, attachment, visible) VALUES ({$director}, {$CFG->USER->USER_ID}, '{$_POST[user]}', '{$_POST[type]}', '{$_POST[cat]}', '{$CFG->oPageInfo->id}', '{$time}', '{$cdate}', '{$_POST[price]}', '{$_POST[text]}', '{$_POST[attachment]}', 1)";
						
						if($CFG->DB->query($sql))
						{
							$o = getSQLRowO("SELECT price,time FROM {$CFG->DB_Prefix}money_accounting WHERE visible='1' ORDER BY cdate DESC limit 1 ");
			
							$status = json_encode(array('status' => 1, 'text' => "Сумма <strong>".$o->price." ".$CFG->USER->USER_CURRENCY."</strong> выдана.<br> Код операции: <strong>".$o->time."</strong>"));
							echo $status; exit;	
						}
						else
						{
							$status = json_encode(array('status' => 0, 'text' => "Произошла ошибка системы, пожалуйста обновите страницу."));
							echo $status; exit;
						}
					}
					else
					{
						$status = json_encode(array('status' => 0, 'text' => "Произошла ошибка системы, пожалуйста обновите страницу."));
						echo $status; exit;
					}

			break;
			
			
			case 'ajax' :
			
				if(is_numeric($CFG->_POST_PARAMS["country"]))
				{
					$director = $CFG->_POST_PARAMS["dir"];
					$type = $CFG->_POST_PARAMS["country"];
					
					$dataS = getSQLArrayO("SELECT * FROM {$CFG->DB_Prefix}money_accounting_type_id WHERE type_id='{$type}' and user_id = '{$director}' and visible=1 order by name ASC");
					
					if($dataS)
					{
						echo '<div class="okcity">';
							echo '<select name="cat" class="selectpicker show-tick">';
							echo '<option value="0" selected>'.$CFG->Locale["fi2"].'</option>';
							for($z=0;$z<sizeof($dataS);$z++)
							{	
								?> 
							  <option value="<?=$dataS[$z]->id?>"><?=$dataS[$z]->name;?></option>
							<? }
							echo '</select>';
						echo '</div>';
						exit;
					}
					elseif($CFG->_POST_PARAMS["country"] == 0)
					{
						echo 0; exit;	
					}
					else
					{
						echo '<select name="cat" class="selectpicker show-tick selajax"  title="Отсутствуют" disabled="disabled" data-style="btn-inverse">';
						echo '</select>';
			
						exit;
					}
					
				}
				exit;
				
			break;

			
			case 'list_view' :
			
			if($CFG->FORM->setForm($_GET)) 
			{
				$data = $CFG->FORM->getFullForm();
				$str = $ACCOUNT->getData($data);

			if($_COOKIE["company"] > 0)
			   {
					$director = $_COOKIE["company"];
			   }
			else
				{
					$director = $CFG->USER->USER_DIRECTOR_ID;
				}
	
	
				if($data["user"] > 0) 	{ $user_id = "AND user_id = '{$data[user]}' ";}
				if($data["type"] > 0)	{ $types = "AND type_id = '{$data[type]}' ";}
				
				$day = date('t');
				
				if($data["year"] > 0 && $data["cdate"] > 0)
				{
					$years = $data["year"];
					$month_clear = $data["cdate"];

					$cdate = "AND (cdate >= '{$years}-{$month_clear}-01 00:00:00') AND (cdate <= '{$years}-{$month_clear}-{$day} 23:59:59')"; 
				}

				if($data["year"] > 0 && $data["cdate"] == 0)
				{
					$years = $data["year"];
					$month_clear = $data["cdate"];
					$cdate = "AND (cdate >= '{$years}-01-01 00:00:00') AND (cdate <= '{$years}-12-31 23:59:59')"; 
				}


				if(!$data["search"] == '')
				{
					$search = str_replace(" ","",$data["search"]);
					
					if(is_numeric($search))
					{
						$apost = apost($data["search"]);		
						$search_where .= " AND (text LIKE '%{$apost}%'  AND director_id = '{$director}' {$user_id}	{$types}	{$cdate}) or (price LIKE '{$search}' AND director_id = '{$director}' {$user_id}	{$types}	{$cdate})";
						
					}
					else
					{
						$namber = explode("*", $data["search"]);
						$count = mb_strlen(trim($namber[1]), 'UTF-8');
							
						if(is_numeric($namber[1]) > 0 && $count == 10)
						{
							$search_where .= " AND (time LIKE '{$namber[1]}' )";
						}
						else
						{
							$apost = apost($data["search"]);		
							$search_where .= " AND (text LIKE '%{$apost}%' AND director_id = '{$director}' {$user_id}	{$types}	{$cdate}) or (price LIKE '%{$apost}%' AND director_id = '{$director}' {$user_id}	{$types}	{$cdate})";
						}
					}
				}
			}

				echo ' <h2><img alt="" src="/tpl/img/new/icon/6_red.png">Бухгалтерия ('.$ACCOUNT->sum($CFG->USER->USER_DIRECTOR_ID).')</h2>';

				echo '<div class="white">';
				
					include("tpl/filter.tpl");				
					
					$l = $ACCOUNT->getList($str, $search_where, 0);
					
					include("tpl/listViewTable_.tpl");
					
				echo '</div>';
				
			break;
				
			case 'record' :

				$cdate = sqlDateNow();
				$time = time();
					
				$sql = "INSERT INTO {$CFG->DB_Prefix}money_accounting (director_id, author_id, user_id, type_id, cat_id, page_id, time, cdate, price, text, visible) VALUES ({$CFG->USER->USER_DIRECTOR_ID}, {$CFG->USER->USER_ID}, '{$_POST[user]}', '{$_POST[type]}', '{$_POST[cat]}', '{$CFG->oPageInfo->id}', '{$time}', '{$cdate}', '{$_POST[price]}', '{$_POST[text]}', 1)";
				
				if($CFG->DB->query($sql))
				{
					$o = getSQLRowO("SELECT price,time FROM {$CFG->DB_Prefix}money_accounting WHERE visible='1' ORDER BY id DESC limit 1 ");
	
					$status = json_encode(array('status' => 1, 'text' => "Сумма <strong>".$o->price." ".$CFG->USER->USER_CURRENCY."</strong> успешно внесена.<br> Код операции: <strong>".$o->time."</strong>"));
					echo $status; exit;	
				}
				else
				{
					$status = json_encode(array('status' => 0, 'text' => "Произошла ошибка системы, пожалуйста обновите страницу."));
					echo $status; exit;
				}
				
			break;
			
			case 'status' :
			
				$id =  apost($_POST["id"]);
				$intro = apost($_POST["desc"]);
				$count = mb_strlen(trim($intro), 'UTF-8');	
				if($count > 1)
				{ 					
					$final = ", intro='{$intro}'";
				}
				else
				{
					$final = "";
				}
				
				$query  = "UPDATE {$CFG->DB_Prefix}money_accounting SET status = '1' {$final} WHERE id='{$id}'";
		   		if($CFG->DB->query($query))
				{
					$o = getSQLRowO("SELECT intro FROM {$CFG->DB_Prefix}money_accounting WHERE id='{$id}'");

					$response = json_encode(array('status' => 1, 'text' => $o->intro));
					echo $response; 
				}
				else
				{
					$response = json_encode(array('status' => 0, 'text' => "Произошла ошибка! Перезагрузим страницу - попробуйте еще раз!"));
					echo $response; 
				}
				
				exit;
				
			break;

			case 'exel' :


						$objPHPExcel = new PHPExcel();

							$objPHPExcel->getProperties()->setCreator("forSign Kazakhstan");
							$objPHPExcel->getProperties()->setLastModifiedBy("forSign Kazakhstan");
							$objPHPExcel->getProperties()->setTitle("XLSX Document");
							$objPHPExcel->getProperties()->setSubject("Export XLSX Document");
						
								$objPHPExcel->setActiveSheetIndex(0);
								
								
								$objPHPExcel->getActiveSheet()->getStyle('A16:F16')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
									
								$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

								$objPHPExcel->getActiveSheet()->getStyle('A:O')->getAlignment()->setWrapText(true);

								//$objPHPExcel->getActiveSheet()->getRowDimension('16')->setRowHeight(30);

								$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(28);
								$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
								$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
								$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
								$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
								$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
								$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
								$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
								$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
								$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
								$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
								$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
								$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
								$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
	
								// Остаток на начала месяца
									$objPHPExcel->getActiveSheet()->SetCellValue('A5', coder("Остаток на начала месяца"));
											
									$m = (date('m')*1)-1;
									$year = date('Y')*1;
									$day = cal_days_in_month(CAL_GREGORIAN, $m, $year);
									
									$res .= "AND (cdate >= '{$year}-{$m}-01 00:00:00') AND (cdate <= '{$year}-{$m}-{$day} 23:59:59')"; 	
									
									$sum = $ACCOUNT->sum_now(85, $res);
									
										if($sum > 0 )
											$sum = $sum;
										else
											$sum = 0;
									$objPHPExcel->getActiveSheet()->SetCellValue('B5', coder($sum));
									$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		
								// Остаток на начала месяца
									
								$objPHPExcel->getActiveSheet()->SetCellValue('A6', coder("Доходы за месяц:"));
																
								// Сумма - приход
									$objPHPExcel->getActiveSheet()->SetCellValue('A7', coder("сумма"));
									$objPHPExcel->getActiveSheet()->getStyle('A7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
																		
									$objPHPExcel->getActiveSheet()->SetCellValue('B7', coder($ACCOUNT->earnings((date('m')*1)+0, 85, 1)));
									$objPHPExcel->getActiveSheet()->getStyle('B7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
								// Сумма - приход

								// Колличество операций - приход
									$objPHPExcel->getActiveSheet()->SetCellValue('A8', coder("действий"));
									$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

									$objPHPExcel->getActiveSheet()->SetCellValue('B8', coder($ACCOUNT->number_sum((date('m')*1)+0, 85, 1)));
									$objPHPExcel->getActiveSheet()->getStyle('B8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
								// Колличество операций - приход
								
								$objPHPExcel->getActiveSheet()->SetCellValue('A9', coder("Расходы за месяц"));

								// Сумма - расхода
									$objPHPExcel->getActiveSheet()->SetCellValue('A10', coder("сумма"));
									$objPHPExcel->getActiveSheet()->getStyle('A10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
																		
									$objPHPExcel->getActiveSheet()->SetCellValue('B10', coder($ACCOUNT->earnings((date('m')*1)+0, 85, 2)));
									$objPHPExcel->getActiveSheet()->getStyle('B10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
								// Сумма - расхода

								// Колличество операций - расхода
									$objPHPExcel->getActiveSheet()->SetCellValue('A11', coder("действий"));
									$objPHPExcel->getActiveSheet()->getStyle('A11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

									$objPHPExcel->getActiveSheet()->SetCellValue('B11', coder($ACCOUNT->number_sum((date('m')*1)+0, 85, 2)));
									$objPHPExcel->getActiveSheet()->getStyle('B11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
								// Колличество операций - расхода

								// Остаток на начала месяца
									$objPHPExcel->getActiveSheet()->SetCellValue('A12', coder("Остаток на конец месяца"));
									$objPHPExcel->getActiveSheet()->getStyle('A12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
										$sum = $ACCOUNT->sum(85);
									
										if($sum > 0 )
											$sum = $sum;
										else
											$sum = 0;
									$objPHPExcel->getActiveSheet()->SetCellValue('B12', coder($sum));
									$objPHPExcel->getActiveSheet()->getStyle('B12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		


									$objPHPExcel->getActiveSheet()->SetCellValue('A15', coder("Все доходы"));
									$objPHPExcel->getActiveSheet()->getStyle('A15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
									
												
								$objPHPExcel->getActiveSheet()->SetCellValue('A16', coder("Сумма"));
									$objPHPExcel->getActiveSheet()->getStyle('A16')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
									$objPHPExcel->getActiveSheet()->getStyle('A16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
																						
								$objPHPExcel->getActiveSheet()->SetCellValue('B16', coder("Код операции"));
									$objPHPExcel->getActiveSheet()->getStyle('B16')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);													
									$objPHPExcel->getActiveSheet()->getStyle('B16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
																						
								$objPHPExcel->getActiveSheet()->SetCellValue('C16', coder("Дата"));
									$objPHPExcel->getActiveSheet()->getStyle('C16')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);					
									$objPHPExcel->getActiveSheet()->getStyle('C16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
																						
								$objPHPExcel->getActiveSheet()->SetCellValue('D16', coder("Счет"));
									$objPHPExcel->getActiveSheet()->getStyle('D16')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);					
									$objPHPExcel->getActiveSheet()->getStyle('D16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
																						
								$objPHPExcel->getActiveSheet()->SetCellValue('E16', coder("Менеджер"));
									$objPHPExcel->getActiveSheet()->getStyle('E16')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);			
									$objPHPExcel->getActiveSheet()->getStyle('E16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
																						
								$objPHPExcel->getActiveSheet()->SetCellValue('F16', coder("Комментарий"));
									$objPHPExcel->getActiveSheet()->getStyle('F16')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);					
									$objPHPExcel->getActiveSheet()->getStyle('F16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
																						

								$m = (date('m')*1)+0;
								$year = date('Y')*1;
								$day = cal_days_in_month(CAL_GREGORIAN, $m, $year);
								
								$str .= "AND (cdate >= '{$year}-{$m}-01 00:00:00') AND (cdate <= '{$year}-{$m}-{$day} 23:59:59')"; 
								$data = $ACCOUNT->getListExel($str, 1);
								
								$cnt = 17;
									
								for ($i=0; $i<sizeof($data); $i++)
								{
									$cnt = $cnt ;

									$objPHPExcel->getActiveSheet()->SetCellValue("A$cnt", coder(number_sum($data[$i]->price)));
										$objPHPExcel->getActiveSheet()->getStyle('A'.$cnt)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
										$objPHPExcel->getActiveSheet()->getStyle('A'.$cnt)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
																							
									$objPHPExcel->getActiveSheet()->SetCellValue("B$cnt", coder($data[$i]->time));
										$objPHPExcel->getActiveSheet()->getStyle('B'.$cnt)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);													
										$objPHPExcel->getActiveSheet()->getStyle('B'.$cnt)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
																							
									$objPHPExcel->getActiveSheet()->SetCellValue("C$cnt", coder($data[$i]->cdate));
										$objPHPExcel->getActiveSheet()->getStyle('C'.$cnt)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);					
										$objPHPExcel->getActiveSheet()->getStyle('C'.$cnt)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
																							
									$objPHPExcel->getActiveSheet()->SetCellValue("D$cnt", coder($data[$i]->cat_id));
										$objPHPExcel->getActiveSheet()->getStyle('D'.$cnt)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);					
										$objPHPExcel->getActiveSheet()->getStyle('D'.$cnt)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
														
														
									$user = SelectData('users', $data[$i]->user_id, 0);								
									$objPHPExcel->getActiveSheet()->SetCellValue("E$cnt", coder($user));
										$objPHPExcel->getActiveSheet()->getStyle('E'.$cnt)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);			
										$objPHPExcel->getActiveSheet()->getStyle('E'.$cnt)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
																							
									$objPHPExcel->getActiveSheet()->SetCellValue("F$cnt", coder($data[$i]->text));
										$objPHPExcel->getActiveSheet()->getStyle('F'.$cnt)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);					
										$objPHPExcel->getActiveSheet()->getStyle('F'.$cnt)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	

									$cnt = $cnt + 1 ;

								}
								
								
								
								$Ycnt = $cnt + 5 ;
								
				
								$objPHPExcel->getActiveSheet()->getStyle("A$Ycnt:F$Ycnt")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
								
								$objPHPExcel->getActiveSheet()->SetCellValue("A$Ycnt", coder("Сумма"));
									$objPHPExcel->getActiveSheet()->getStyle('A'.$Ycnt)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
									$objPHPExcel->getActiveSheet()->getStyle('A'.$Ycnt)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
																						
								$objPHPExcel->getActiveSheet()->SetCellValue("B$Ycnt", coder("Код операции"));
									$objPHPExcel->getActiveSheet()->getStyle('B'.$Ycnt)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);													
									$objPHPExcel->getActiveSheet()->getStyle('B'.$Ycnt)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
																						
								$objPHPExcel->getActiveSheet()->SetCellValue("C$Ycnt", coder("Дата"));
									$objPHPExcel->getActiveSheet()->getStyle('C'.$Ycnt)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);					
									$objPHPExcel->getActiveSheet()->getStyle('C'.$Ycnt)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
																						
								$objPHPExcel->getActiveSheet()->SetCellValue("D$Ycnt", coder("Счет"));
									$objPHPExcel->getActiveSheet()->getStyle('D'.$Ycnt)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);					
									$objPHPExcel->getActiveSheet()->getStyle('D'.$Ycnt)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
																						
								$objPHPExcel->getActiveSheet()->SetCellValue("E$Ycnt", coder("Менеджер"));
									$objPHPExcel->getActiveSheet()->getStyle('E'.$Ycnt)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);			
									$objPHPExcel->getActiveSheet()->getStyle('E'.$Ycnt)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
																						
								$objPHPExcel->getActiveSheet()->SetCellValue("F$Ycnt", coder("Комментарий"));
									$objPHPExcel->getActiveSheet()->getStyle('F'.$Ycnt)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);					
									$objPHPExcel->getActiveSheet()->getStyle('F'.$Ycnt)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
								
								$cnt = $cnt + 6 ;
								
								$data = $ACCOUNT->getListExel($str, 2);
								
								for ($i=0; $i<sizeof($data); $i++)
								{
									$cnt = $cnt ;

									$objPHPExcel->getActiveSheet()->SetCellValue("A$cnt", coder(number_sum($data[$i]->price)));
										$objPHPExcel->getActiveSheet()->getStyle('A'.$cnt)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
										$objPHPExcel->getActiveSheet()->getStyle('A'.$cnt)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
																							
									$objPHPExcel->getActiveSheet()->SetCellValue("B$cnt", coder($data[$i]->time));
										$objPHPExcel->getActiveSheet()->getStyle('B'.$cnt)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);													
										$objPHPExcel->getActiveSheet()->getStyle('B'.$cnt)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
																							
									$objPHPExcel->getActiveSheet()->SetCellValue("C$cnt", coder($data[$i]->cdate));
										$objPHPExcel->getActiveSheet()->getStyle('C'.$cnt)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);					
										$objPHPExcel->getActiveSheet()->getStyle('C'.$cnt)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
																							
									$objPHPExcel->getActiveSheet()->SetCellValue("D$cnt", coder($data[$i]->cat_id));
										$objPHPExcel->getActiveSheet()->getStyle('D'.$cnt)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);					
										$objPHPExcel->getActiveSheet()->getStyle('D'.$cnt)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
																							
									$user = SelectData('users', $data[$i]->user_id, 0);	
									$objPHPExcel->getActiveSheet()->SetCellValue("E$cnt", coder($user));
										$objPHPExcel->getActiveSheet()->getStyle('E'.$cnt)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);			
										$objPHPExcel->getActiveSheet()->getStyle('E'.$cnt)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
																							
									$objPHPExcel->getActiveSheet()->SetCellValue("F$cnt", coder($data[$i]->text));
										$objPHPExcel->getActiveSheet()->getStyle('F'.$cnt)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);					
										$objPHPExcel->getActiveSheet()->getStyle('F'.$cnt)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	

									$cnt = $cnt + 1 ;

								}
	

								$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
								
								$name = './documents/buch/'.date("m_y").".xlsx";
								
								$objWriter->save($name);

								$filename = './'.$name;
								
								if (file_exists($name)) 
								{
									$headers    = "MIME-Version: 1.0;$EOL";   
									$headers   .= "Content-Type: multipart/mixed; boundary=\"$boundary\"$EOL";  
									$headers   .= "From: Информационная «База клиентов» <support@led.ru>";  
									  
									$text  = "<a href='http://".$_SERVER[SERVER_NAME].substr($name, 1)."' target='_blank'>Скачать отчет за ".date("m Y")." </a>";
									
									//0077010323333@mail.ru
									mailer('gnezdilov.gena@mail.ru, 0077010323333@mail.ru', 'Бухгалтерия, Отчет', $text );

								} 

								$objPHPExcel->disconnectWorksheets();
								unset($objPHPExcel);

								exit;	

			break;
							
				
				
	
			default:
			
				if($CFG->USER->USER_ACCES_BUCH > 0)
				{
					include("tpl/body.tpl");
				}
				else
				{
					$CFG->STATUS->ERROR = true; 
					$CFG->STATUS->MESSAGE = 'Вам закрыт доступ в эту страницу';
					redirect($_SERVER["HTTP_REFERER"]);
				}

			break;
	
		}
