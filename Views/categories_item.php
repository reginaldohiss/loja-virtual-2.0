<?php foreach($itens as $item): ?>
  <tr>
    <td><?php
    for($q=0;$q<$level;$q++) echo '-- ';
    echo $item['name'];
    ?></td>
    <td>
      <div class="btn-group">
        <a href="<?php echo BASE_URL.'categories/edit/'.$item['id']; ?>" class="btn btn-xs btn-primary">Editar</a>
        <a href="<?php echo BASE_URL.'categories/del/'.$item['id']; ?>" class="btn btn-xs btn-danger">Excluir</a>
      </div>
    </td>
  </tr>

  <?php
  if(count($item['subs']) > 0) {
    $this->loadView('categories_item', array(
      'itens' => $item['subs'],
      'level' => $level + 1
    ));
  }
  ?>

<?php endforeach; ?>