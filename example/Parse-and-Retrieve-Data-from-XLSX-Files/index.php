<html>
<head><title>PHP: Parse and Retrieve Data from XLSX Files | SPYROZONE.NET</title>
<style>
<!---
a{text-decoration:none;color:#0000FF;}
a:hover{text-decoration:underline;color:#0000FF;}
a:visited{text-decoration:none;color:#0000FF;}
a:active{text-decoration:none;position: relative;top: 1px;}
h1{font-family: "Arial","Verdana","Lucida Sans Unicode"; font-size: 16pt; line-height:150%; margin-top:20; margin-bottom:0 ;text-align:center;}
h2{font-family: "Arial","Verdana","Lucida Sans Unicode"; font-size: 12pt; font-weight: bold; text-decoration: underline; line-height:150%; margin-top:40; margin-bottom:10 }
h3{font-family: "Arial","Verdana","Lucida Sans Unicode"; font-size: 11pt; line-height:150%; margin-top:20; margin-bottom:0}
ul{font-family: "Arial","Verdana","Lucida Sans Unicode"; font-size: 11pt;word-spacing: 0; line-height: 150%; margin-top: 0; margin-bottom: 0}
p{font-family: "Arial","Verdana","Lucida Sans Unicode"; font-size: 11pt }
#datacontent{margin-left:20pt}
#footer{ font-family: "Arial","Verdana","Lucida Sans Unicode"; font-size: 10pt; line-height:150%; margin-top:40; margin-bottom:0;text-align:center}
#xlsxTable{font-family: "Arial","Verdana","Lucida Sans Unicode";font-size: 11pt;margin: 15px;text-align: left;border-collapse: collapse;}
#xlsxTable th{padding: 8px;font-weight: normal;font-size: 13px;color: #039;background: #b9c9fe;}
#xlsxTable td{padding: 8px;background: #e8edff;border-top: 1px solid #fff;color: #669;}
#xlsxTable tbody tr:hover td{background: #d0dafd;}
//--->
</style>
</head>
<body>
<h1>PHP: Parse and Retrieve Data from XLSX Files</h1>
<div id="datacontent">
<h2>Upload *.xlsx File:</h1>
<form method="post" enctype="multipart/form-data">
<p>File: <input type="file" name="file"  /><input type="submit" value="Parse" /></p>
</form>
<h2>Example XLSX File:</h1>
<ul><li><a href="example-data.xlsx">example-data.xlsx</a></li></ul>
<h2>Download the source:</h1>
<ul><li><a href="Parse-and-Retrieve-Data-from-XLSX-Files.zip">Parse-and-Retrieve-Data-from-XLSX-Files.zip</a></li></ul>
<h2>About:</h1>
<p>Parse and Retrieve Data from XLSX Files based on "simplexlsx.class.php" by Sergey Shuchkin under <a rel="nofollow" href="http://www.opensource.org/licenses/artistic-license.html">Artistic License</a>. I've added function getWorksheetName() to get worksheet name.</p>
</div>
<?php
if((!empty($_FILES["file"])) && ($_FILES['file']['error'] == 0)) {
	
	$limitSize	= 15000; //(15 kb) - Maximum size of uploaded file, change it to any size you want
	$fileName	= basename($_FILES['file']['name']);
	$fileSize	= $_FILES["file"]["size"];
	$fileExt	= substr($fileName, strrpos($fileName, '.') + 1);
	
	if (($fileExt == "xlsx") && ($fileSize < $limitSize)) {
		
		require_once "simplexlsx.class.php";
		$getWorksheetName = array();
		$xlsx = new SimpleXLSX( $_FILES['file']['tmp_name'] );
		$getWorksheetName = $xlsx->getWorksheetName();
		echo '	<hr>
				<div id="datacontent">
				<h1>Result</h1>
		';
		echo '<h2>File Info:</h1><ul>';
		echo '<li><b>File Name:</b> '.$fileName.'</li>';
		echo '<li><b>File Size:</b> '.($fileSize/1000).' kb</li></li>';
		echo '</ul>
		
		<h2>Worksheets:</h1><ul>';
		foreach ($getWorksheetName as $value) {
			echo '<li>'.$value.'</li>';
		}
		echo '</ul>';
		
		echo '<h2>Display data in table format:</h2>
		<div id="datacontent">';
		for($j=1;$j <= $xlsx->sheetsCount();$j++){
			echo '<h3>Worksheet Name: '.$getWorksheetName[$j-1].'</h1>';
			echo '<table id="xlsxTable">';
			list($cols,) = $xlsx->dimension($j);
			//Prepare table
			foreach( $xlsx->rows($j) as $k => $r) {
				if ($k == 0){
					$trOpen		= '<th';
					$trClose	= '</th>';
					$tbOpen		= '<thead>';
					$tbClose	= '</thead>';
				}else{
					$trOpen		= '<td';
					$trClose	= '</td>';
					$tbOpen		= '<tbody>';
					$tbClose	= '</tbody>';
				}
				echo $tbOpen;
				echo '<tr>';
				for( $i = 0; $i < $cols; $i++)
					//Display data
					echo $trOpen.'>'.( (isset($r[$i])) ? $r[$i] : '&nbsp;' ).$trClose;
				echo '</tr>';
				echo $tbClose;
			}
			echo '</table>';
		}
		echo '</div>
		
		<h2>Display as Array:</h2>
		<div id="datacontent" style="overflow: auto; height: 400px; width: 550px; border: 1px #008080 solid;">';
		echo '<h3>$xlsx->getWorksheetName()</h1>';
		echo '<pre>';
		print_r($xlsx->getWorksheetName());
		echo '</pre>';
		for($j=1;$j <= $xlsx->sheetsCount();$j++){
			echo '<h3>$xlsx->rows('.$j.')</h1>';
			echo '<pre>';
			print_r( $xlsx->rows($j) );
			echo '</pre>';
			echo '<h3>$xlsx->rowsEx('.$j.')</h1>';
			echo '<pre>';
			print_r( $xlsx->rowsEx($j) );
			echo '</pre>';
		}
		echo '	</div>
				</div>
				<hr>';
	}else{
		echo '<script>alert("Sory, this demo page only allowed .xlsx file under '.($limitSize/1000).' Kb!\nIf you want to try upload larger file, please download the source and try it on your own webserver.")</script>';
	}
}
?>

<div id="footer">&copy; 2011++ SPYRO KiD | http://www.spyrozone.net<br>All Rights Reserved</div>
</body>
</html>
