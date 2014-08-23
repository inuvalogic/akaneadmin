<?php
	/* untuk custom template atau script */
	/* simpan di file ini */
	
	if ( isset($_GET['content']) ){
		define('BACKLINK', '<a class="back-menu" href="'.SITEURL.'?content='.$_GET['content'].'">Back</a><br /><br />');
	}
	
	$lang2['ADDONJS'] = '
<script type="text/javascript">
var main_site_url = "'.SITEURL.'";
var main_site_path = "'.SITEPATH.'";
var pathToPhp = "'.SITEPATH.'tiny_mce/tinyupload.php";
</script>
<script type="text/javascript" src="'.SITEURL.'tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="'.SITEURL.'tiny_mce/tinyupload.js"></script>
<script type="text/javascript">
	$().ready(function() {
		$(\'textarea.tinymce\').tinymce({
			script_url : "'.SITEURL.'tiny_mce/tiny_mce.js",
			width: 1000,
			height: 450,
			theme : "advanced",
			plugins: "paste,inlinepopups",
			theme_advanced_buttons1 : "pasteword,|,fontselect,fontsizeselect,bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,numlist,bullist,|,forecolor,|,link,code",
			theme_advanced_buttons2 : "",
			theme_advanced_buttons3 : "",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
			relative_urls : false,
			file_browser_callback:tinyupload,
			content_css : "'.SITEURL.'tiny_mce/tinymce.css"
		});
		$(\'.confirm_delete\').click(function(){
			var answer = confirm(\'Are you sure to delete "\'+jQuery(this).attr(\'title\')+\'"\');
			return answer;
		}); 
		$(\'.color_picker\').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				$(el).val(hex);
				$(el).ColorPickerHide();
			},
			onBeforeShow: function () {
				$(this).ColorPickerSetColor(this.value);
			}
		})
		.bind(\'keyup\', function(){
			$(this).ColorPickerSetColor(this.value);
		});
		$(\'.datepicker\').datepicker({dateFormat: "yy-mm-dd"});
		$(\'.datetimepicker\').datetimepicker({dateFormat: "yy-mm-dd", timeFormat: "HH:mm:ss"});
	});
</script>';

?>