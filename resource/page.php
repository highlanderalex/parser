<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="keywords" description="Parser">
		<meta name="description" description="Parser">
		<title>Parser Test task</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	</head>
<body>
<div style="width: 750px; margin: 50px;">
	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title">Результаты работы скрипта:</h3>
		</div>
		<div class="panel-body">
			<div class="alert alert-success" role="alert">
				<b>Начало работы: </b><?=$data['start'];?><br>
				<b>Конец работы: </b><?=$data['end'];?>
			</div>
			<?php if(isset($data['error']) && !empty($data['error'])) :?>
				<div class="alert alert-danger" role="alert">
					<b>Ошибки:</b> <br>
					<?php foreach($data['error'] as $error) : ?>
						<?=$error;?><br>
					<?php endforeach; ?>
					
				</div>
			<?php endif;?>
		</div>
	</div>
</div>
</body>
</html>