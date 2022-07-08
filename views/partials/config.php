<?php
// $id_product
?>
<section id="section-config">
<h2>Producto precio variable</h2>
    <table class="form-table id-product-section">
            <tbody>
                <tr id="id-product-section">
                    <th scope="row">
                        <?php _e('ID de producto precio variable','dcms-parent-course') ?>
                    </th>
                    <td>
                        <input type="number" id="id-product" name="id-product" value="<?= $id_product ?>">
                        <button class="button button-primary">
                            <?php _e('Save', 'dcms-parent-course'); ?>
                        </button>
                        <span class="msg-btn"></span>
                        <div class="dcms-spin hide"></div>
                    </td>
                </tr>
            </tbody>
    </table>
</section>


<section>
<h2>Shortcode</h2>
<p>
Usar el siguiente shortcode en la lección de un curso para ver la lista de módulos que tiene el curso:
<strong>[mostrar_modulos]</strong>
</p>

</section>