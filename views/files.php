
<?php $app->loadView('partials/header.php');?>


<div class="card">
  <div class="card-divider">
  	<strong>Максимальное количество активных сессий</strong>
  </div>
  <div class="card-section">
	<div class="grid-x grid-margin-x">
		<?php foreach ($group as $day => $count): ?>
		<div class="cell small-2">
			<strong><?=$day;?></strong>&ensp;
			<strong class="label success"><?=$count;?></strong>
		</div>
		<?php endforeach;?>
	</div>
  </div>
</div>

<form method="POST" enctype="multipart/form-data" action="/generateFiles">
	<div class="grid-x grid-margin-x">
		<div class="cell">
			<div class="grid-x grid-margin-x">
				<div class="cell small-6">

					<div class="input-group">
					  <span class="input-group-label">Все</span>
					  <div class="input-group-button">
					    <strong class="label"><?=count($items);?></strong>
					  </div>
					</div>
				</div>
				<div class="cell small-6">
					<div class="float-right">
						<div class="input-group">
						  <span class="input-group-label">Сколько</span>
						  <input class="input-group-field" type="number" name="howMany" min="1" value="4">
						  <div class="input-group-button">
						    <button class="button secondary" value="show" name="submit">Создавать</button>
						  </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<!-- <div class="grid-x grid-margin-x medium-margin-collapse"> -->
<div class="grid-x grid-padding-x">
<?php foreach ($items as $item): ?>
    <div class="cell small-3">
        <div class="card">
		  <div class="card-divider">
		  	<strong>
			    <!-- Начинал от:&ensp; -->
			    <small>
			    	<?=$item['startDate'];?>
			    </small>
		  	</strong>
		  </div>
		  <div class="card-section">
		    <small><?=$item['startDateString'];?></small>&ensp;
		  	<small>
		  		Начинал
		  	</small>
		    <br>
		    <small><?=$item['endDateString'];?></small>&ensp;
		  	<small>
		  		Конец
		  	</small>
		  </div>
		  <div class="card-divider">
		  	<small>
		  	<?=$item['id'];?>
		  	</small>
		  </div>
		</div>
    </div>
<?php endforeach;?>
</div>


<?php $app->loadView('partials/footer.php');?>