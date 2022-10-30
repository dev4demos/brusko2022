
<?php $app->loadView('partials/header.php');?>

<form method="POST" enctype="multipart/form-data" action="/palindrome">

<!-- Palindrome -->
<div class="grid-x grid-margin-x">
	<div class="cell small-6">
		<div class="card">
		  <div class="card-divider">
		  	<!-- <strong>Palindrome</strong> -->
		  	<strong>Палиндромы</strong>
		  </div>
		  <div class="card-section">
		    <label>
			  <!-- Words? -->
			  Слова?
			  <input type="text" name="words[]" value="шалаш">
			  <input type="text" name="words[]" value="dad">
			  <input type="text" name="words[]" value="папа">
			</label>
			<label>
			  <!-- Numbers? -->
			  Числа?
			  <input type="number" name="numbers[]" value="673">
			  <input type="number" name="numbers[]" value="99">
			  <input type="number" name="numbers[]" value="112">
			</label>
		  </div>
		  <div class="card-section">
			<div class="small button-group float-right">
			  <button class="button" value="palindrome" name="submit">
			  <!-- Разместить -->
			  Получить результат
			 </button>
			</div>
		  </div>
		</div>
	</div>
	<div class="cell small-6">
		<div class="card">
		  <div class="card-divider">
		  	<!-- <strong>Result</strong> -->
		  	<strong>Результат</strong>
		  </div>
		  <div class="card-section">
		  	<ol>
			<?php foreach ($items as $word => $ans): ?>
			    <li>
			        <!-- Is&ensp;<strong><?=$app->e($word);?></strong>&ensp;palindrome? - <strong><?=$app->e($ans) ? 'Yes' : 'No';?></strong> -->
			        Является&ensp;ли&ensp;<strong><?=$app->e($word);?></strong>&ensp;палиндромом? - <strong><?=$app->e($ans) ? 'Да' : 'Нет';?></strong>
			    </li>
			<?php endforeach;?>
			</ol>
		  </div>
		</div>
	</div>
</div>

</form>

<?php $app->loadView('partials/footer.php');?>