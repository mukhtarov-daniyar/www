<?php

set_time_limit(6000);

$zz=array();/*массив для путей к файлам*/
$not=array();/*массив для исключений*/

/*Название архива*/
$ZipName="backup/file/site/backup_site_".date('d_m_Y_H_i').".zip";
/*Исключения*/

$not[count($not)]=$ZipName;
$not[count($not)]='io.php';
$not[count($not)]='admin';
$not[count($not)]='backup';
$not[count($not)]='tmp';
$not[count($not)]='old_';
$not[count($not)]='documents';
$not[count($not)]='filebox';


/*С какой папки тащим*/
wfold('');
//wfold('tpl/');
//wfold('tpl/');


function wfold($url){

        global $zz;/*добавляем массивы в функцию*/
        global $not;

        $mm = glob($url ."*");/*в массив список, что в папке*/
        for($i=0;$i<count($mm);$i++){/*Идем по всему массиву*/

                $nn=0;/*Счетчик исключений*/
                for($i2=0;$i2<count($not);$i2++){/*Есть ли он в исключении?*/
                        if($mm[$i] == $not[$i2]){$nn=1;}
                }
                if($nn==0){
                        if(is_dir($mm[$i])){/*Если это папка*/
                                wfold($mm[$i] . '/');/*Рекурсивно запускаем эту же функцию*/
                        }else{/*это файл*/
                                $zz[count($zz)]=$mm[$i];/*добавляем в массив*/
                        }
                }

        }
}


$zip=new ZipArchive();/*Подключаем библиотеку архива*/
if($zip->open($ZipName, ZipArchive::CREATE)!==TRUE){/*Удачное ли создание*/
        exit('CreateZIP[X]');
}
for($i=0;$i<count($zz);$i++){/*Из массива в архив*/
        $zip->addFile($zz[$i]);
}
/*Показ размерности и закрытие*/
echo $ZipName.'[FileKol=' . $zip->numFiles . ';FileSize=';
$zip->close();
echo (filesize($ZipName))/1000000 . 'mb;]';

?>
