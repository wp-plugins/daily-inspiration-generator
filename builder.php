<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="FireG" />

	<title>Daily Inspiration Builder</title>
    
    <style type="text/css">
    *{padding: 0; margin: 0;}
    #loading {height: 375px; width: 500px; margin: 10% auto; display: block;}
    </style>
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        $.get('proxy.php', function(data){
            var convertedData = $(data).text();
            $('#buffer').html(convertedData);
            
            $('#buffer').find('img').appendTo('#output');
            $('#output img').removeAttr('width');
            $('#buffer').remove();
            $('head:last').remove();
            var output = $('#output').html();
            $('input#filtered').attr('value',output);
            $('#builder').click();
        });
    });
    </script>
</head>

<body>

<div id="loading"><img src="http://mapsonline.dundeecity.gov.uk/DCC_GIS_ROOT/dcc_GIS_Config/images/loading.gif" alt="" /></div>

<div id="buffer" style="display: none;"></div>
<div id="output" style="display: none;"></div>

<?php if(!isset($_SERVER['HTTP_REFERER'])) $referer = $_POST['referer']; else $referer = $_SERVER['HTTP_REFERER']; if(isset($_GET['post_referer'])) $referer = $_GET['post_referer']; ?>
<form action="<?php echo $referer; ?>" method="post" style="display: none;">
    <input type="hidden" value="" name="filtered" id="filtered" />
    <input type="hidden" value="<?php echo $referer; ?>" name="referrer" id="referrer" />
    <input type="submit" name="builder" id="builder" />
</form>

</body>
</html>