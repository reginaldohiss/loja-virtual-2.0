<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Opções
  </h1>
</section>

<!-- Main content -->
<section class="content container-fluid">

	<form method="POST" action="<?php echo BASE_URL; ?>options/edit_action/<?php echo $info['id']; ?>">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title">Editar Opção</h3>
				<div class="box-tools">
					<input type="submit" class="btn btn-success" value="Salvar" />
				</div>
			</div>
			<div class="box-body">

				<div class="form-group <?php echo (in_array('name', $errorItems))?'has-error':''; ?>">
					<label for="option_name">Título da página</label>
					<input type="text" class="form-control" id="option_name" name="name" value="<?php echo $info['name']; ?>" />
				</div>

			</div>
		</div>
	</form>

</section>
<!-- /.content -->