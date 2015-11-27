<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>Youtube video - demo</title>

	<!-- Bootstrap -->
	<link href="./static/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
<pre>
<?php

require('./../Document.php');
require('./../WorkBook.php');
require('./../Sheet.php');
require('./../BsmFileHelper.php');

use Brisum\Lib\Xlsx\Document;
use Brisum\Lib\Xlsx\WorkBook;

$document = new Document(
		realpath(__DIR__ . '/export-products-18-11-15_09-38-32.xlsx'),
		realpath(__DIR__ . '/tmp')
);
//$document->extract();

$workBook = new WorkBook($document);
$sheets = $workBook->getSheetList();
$sheet = $workBook->getSheetById(1);
$rows = $sheet->getRows(15, 3);

?>
</pre>

<table class="table table-bordered table-striped">
	<thead>
	<tr>
		<td></td>
		<?php foreach (array_keys(reset($rows)) as $cellName) : ?>
			<td>
				<?php echo $cellName; ?>
			</td>
		<?php endforeach; ?>
	</tr>
	</thead>
	<tbody>
		<?php foreach ($rows as $rowNumber => $row) : ?>
			<tr>
				<td>
					<?php echo $rowNumber; ?>
				</td>
				<?php foreach ($row as $cell) : ?>
					<td>
						<?php echo $cell; ?>
					</td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="./static/js/jquery-2.1.4.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script type="./text/javascript" src="static/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
