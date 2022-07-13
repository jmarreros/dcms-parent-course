<?php
// Muestra un metabox en los cursos que son módulos para seleccionar el curso padre
// Los cursos padres mostrados excluyen otros módulos

use dcms\parent\includes\Database;
use dcms\parent\includes\Process;

add_action( 'add_meta_boxes', 'dcms_add_metabox_parent_course' );
function dcms_add_metabox_parent_course(){
  add_meta_box(
      'dcms_metabox_parent_course',
      'Curso padre para el módulo',
      'dcms_add_metabox_parent_content',
      'stm-courses',
      'side'
  );
}

function dcms_add_metabox_parent_content( $post ){
  $title = strtoupper($post->title);
  if ( ! str_starts_with($title, 'MÓDULO') ){
    echo "<div><strong>⚠️ Esta entrada debe tener un título que empiece por: 'Módulo ...' para tener un curso padre</strong></div>";
  }
  $db = new Database;
  $courses = $db->get_courses();
  $parent_id = $db->get_parent_course($post->ID);
  $order_module = get_post_meta($post->ID, 'order-module', true);
  ?>
  <br>
    <select name="parent-course">
      <option value="0">Ninguno</option>
      <?php foreach ($courses as $course): ?>
        <option value="<?= $course->ID ?>" <?php selected($parent_id, $course->ID) ?> ><?= $course->post_title ?></option>
      <?php endforeach; ?>
    </select>
  <div class="order-module">
  <label>Orden</label>
  <input type="number" value="<?= $order_module ?>" name="order-module">
  </div>
  <?php
}

add_action( 'save_post_stm-courses', 'dcms_save_metabox_parent_content', 20, 2 );
function dcms_save_metabox_parent_content( $post_id, $post ){
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    $parent_id = $_POST['parent-course']??0;
    $order_module = $_POST['order-module']??0;

    // Save metabox
    $db = new Database;
    $db->save_module_parent_course($post_id, $parent_id);
    update_post_meta($post_id, 'order-module', $order_module);

    // Update users course with new module
    // TODO: If parent_id = 0, remove
    if ( $db->exists_users_without_new_module($parent_id) ) {
      $process = new Process;
      $process->update_user_course_without_module($parent_id);  
    }
}

