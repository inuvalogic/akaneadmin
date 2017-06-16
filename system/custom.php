<?php
	/* untuk custom template atau script */
	/* simpan di file ini */
	
	if ( isset($_GET['content']) ){
		define('BACKLINK', '<a class="backlink" href="'.SITEURL.'?content='.$_GET['content'].'"><span class="fa fa-arrow-left"></span> &nbsp;Back to List</a><br /><br />');
	}

	$tinymce_js = '<script type="text/javascript" src="'.SITEURL.'tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
    selector: "textarea.tinymce",
    menubar : false,
    plugins: "paste, textcolor, code, image, jbimages",
    paste_word_valid_elements: "b,strong,i,em,h1,h2",
    toolbar: ["bold italic underline | alignleft aligncenter alignright alignjustify | numlist bullist | forecolor backcolor | image jbimages | link code"],
});
</script>';

	$lang2['ADDONJS'] = '
<script type="text/javascript">
var main_site_url = "'.SITEURL.'";
var main_site_path = "'.SITEPATH.'";
</script>
'.$tinymce_js;
