<?php
$javadir = JAVA_DIR;
if (is_dir($javadir)) {
	$lang2['JAVASCRIPT'] = '';
    if ($dhj = opendir($javadir)) {
        while (($file_java = readdir($dhj)) !== false) {
			if ( ($file_java!=".") && ($file_java!="..") && ($file_java!="load.php") ) {
				$files_java[] = $file_java;
			}
       }
	   $count_java = count($files_java);
	   sort($files_java);
	   if ($count_java!=0){
		   for ($a=0;$a<$count_java;$a++)
		   {
			 $lang2['JAVASCRIPT'] .= '<script language="javascript" src="'.$javadir.$files_java[$a].'"></script>'."\n";
		   }
	   }
	   closedir($dhj);
    }
}
?>