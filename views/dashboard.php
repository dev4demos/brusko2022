
<?php $app->loadView('partials/header.php');?>


<?php

$fromDate = $app->request()->input['fromDate'] ?: '1970-01-01';

$toDate = $app->request()->input['toDate'] ?: date('Y-m-d');
?>

<form method="POST" enctype="multipart/form-data">

	<div class="grid-x grid-margin-x">
		<div class="cell">
			<div class="card">
			  <div class="card-divider">
			  	<!-- <strong>Orders</strong> -->
			  	<strong>Заказы</strong>
			  </div>
			  <div class="card-section">
			  	<!-- searchbar -->
				<div class="grid-x grid-margin-x">
					<div class="cell small-6">
						<div class="input-group">
						  <!-- <span class="input-group-label">Filter:</span>&ensp; -->
						  <!-- <span class="input-group-label">Фильтр:</span>&ensp; -->
						  <!-- <span class="input-group-label">From</span> -->
						  <span class="input-group-label">Из</span>
						  <input class="input-group-field" type="date" name="fromDate" min="1970-01-01" value="<?=$fromDate;?>" required>
						  <!-- <span class="input-group-label">To</span> -->
						  <span class="input-group-label">До</span>
						  <input class="input-group-field" type="date" name="toDate" min="1970-01-01" value="<?=$toDate;?>" >
						  <div class="input-group-button">
						    <button class="button secondary" value="show" name="submit">Показать</button>
						  </div>
						</div>
					</div>
					<div class="cell small-6">
						<div class="small button-group float-right">
						  <button class="button warning" type="reset" name="submit">Перезагрузить</button>
						  <!-- &ensp; -->
						  <a class="button" href="/dashboard">Показать все</a>
						</div>
					</div>
				</div>
			  </div>
			  <div class="card-section">
			    <table class="stack">
				  <thead>
				    <tr>
				      <th>ID</th>
				      <!-- <th>Name</th> -->
				      <th>Имя</th>
				      <!-- <th>Date</th> -->
				      <th>Дата создания</th>
				      <!-- <th>Orders</th> -->
				      <th>Заказы</th>
				      <!-- <th>Price</th> -->
				      <th>Цена</th>
				      <!-- <th>Comment</th> -->
				      <th>Комментарий</th>
				      <!-- <th>order_latest</th> -->
				      <th>Дата последний заказ</th>
				    </tr>
				  </thead>
				  <tbody>

				<?php foreach ($items as $item): ?>
				    <tr>
				      <td><?=$app->e($item->id);?></td>
				      <td><?=$app->e(ucwords($item->name));?></td>
				      <td><?=$app->e($item->data_create);?></td>
				      <td><?=$app->e($item->orders);?></td>
				      <td>
				      	<?=money_format("₽ %i", (float) $app->e($item->price));?>
				      </td>
				      <td><?=$app->e($item->comment);?></td>
				      <td><?=$app->e($item->order_latest);?></td>
				    </tr>
				<?php endforeach;?>

				  </tbody>
				</table>
			  </div>
			  <div class="card-section">
				<div class="grid-x grid-margin-x">
					<div class="cell small-6">
						<div class="input-group">
						  <!-- <span class="input-group-label">Download Name</span> -->
						  <span class="input-group-label">Имя загрузки</span>
						  <input class="input-group-field" type="text" name="downloadName" value="заказы">
						</div>
			  		</div>
					<div class="cell small-6">
						<div class="small button-group float-right">
						  <button class="button secondary" value="xlsxDownload" name="submit">Скачать XLSX</button>
						  <button class="button secondary" value="xlsDownload" name="submit">Скачать XLS</button>
						  <button class="button secondary" value="csvDownload" name="submit">Скачать CSV</button>
						</div>
			  		</div>
			  	</div>
			  </div>
			</div>
		</div>
	</div>

</form>

<?php $app->loadView('partials/footer.php');?>