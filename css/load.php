<?php
$cssdir = CSS_DIR;
if (is_dir($cssdir)) {
	$lang2['CSS'] = '';
    if ($dhc = opendir($cssdir)) {
        while (($file_css = readdir($dhc)) !== false) {
            if ( ($file_css!=".") && ($file_css!="..") && ($file_css!="load.php") ) {
				$files_css[] = $file_css;
			}
        }
	   $count_css = count($files_css);
	   sort($files_css);
	   if ($count_css!=0){
		   for ($a=0;$a<$count_css;$a++)
		   {
				$lang2['CSS'] .= '<style type="text/css" media="screen">@import "'.$cssdir.$files_css[$a].'";</style>'."\n";
		   }
	   }
	   closedir($dhc);
    }
}
?>