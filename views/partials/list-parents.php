<?php
defined( 'ABSPATH' ) || exit;

$course_url = admin_url() . 'post.php?post=';
$module_url = admin_url() . 'post.php?post=';

// post.php?post=21458&action=edit

?>
<div id="list-parents" class="wrap" >

  <h1><?php _e('Cursos con módulos', 'dcms-parent-course') ?></h1>

  <div style="overflow-x: auto;">
    <table class="dcms-table-report striped" id="table-parents">
      <tr>
          <th>Curso</th>
          <th>Módulo</th>
      </tr>
      <?php foreach ($courses_modules as $key => $item): ?>
        <tr>
          <td><a href="<?= $course_url.$item->course_id ?>&action=edit" target="_blank"><?= $item->course_title ?></a></td>
          <td><a href="<?= $module_url.$item->module_id ?>&action=edit" target="_blank"><?= $item->module_title ?></a></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>

</div>