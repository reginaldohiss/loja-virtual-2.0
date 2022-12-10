<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Opções do Produto
  </h1>
</section>

<!-- Main content -->
<section class="content container-fluid">

  <div class="box">
    <div class="box-header">
      <h3 class="box-title">Opções do Produto</h3>
      <div class="box-tools">
        <a href="<?php echo BASE_URL.'options/add'; ?>" class="btn btn-success">Adicionar</a>
      </div>
    </div>
    <div class="box-body">
      <table class="table">
        <tr>
          <th>Nome da opção</th>
          <th width="130">Ações</th>
        </tr>

        <?php foreach($list as $item): ?>
        	<tr>
        		<td><?php echo $item['name']; ?></td>
        		<td>
        			<div class="btn-group">
						<a href="<?php echo BASE_URL.'options/edit/'.$item['id']; ?>" class="btn btn-xs btn-primary">Editar</a>
						<a href="<?php echo BASE_URL.'options/del/'.$item['id']; ?>" class="btn btn-xs btn-danger <?php echo ($item['product_count']!='0')?'disabled':''; ?>">Excluir</a>
					</div>
        		</td>
        	</tr>
        <?php endforeach; ?>

      </table>

    </div>
  </div>

</section>
<!-- /.content -->