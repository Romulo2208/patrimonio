









<div class="tab-content">
  <div id="item" class="tab-pane in active">
      <!-- <p>
          <a href="<?php echo $this->Html->url(array('action' => 'add'), true); ?>" class="btn btn-small btn-inverse icon-edit popup"  data-rel="doc"> Novo Pedido</a>
      </p> -->
      <hr />

      <table class="table table-bordered table-striped">
          <thead>
              <tr>
                  <th style="width: 150px;">Quantidade</th>
                  <th style="width: 300px;">Descri&ccedil;&atilde;o do Produto</th>
                  <th style="width: 250px;">Codigo Produto</th>
                  <th style="width: 50px;">Aplica&ccedil;&atilde;o</th>
                  <th style="width: 150px;"></th>
              </tr>
          </thead>
          <tbody>
              <?php foreach ($pedidos as $pedido) { ?>
                <tr>
                    <td><?php echo $pedido['PedidoItem']['quantidade_pedido']; ?></td>
                    <td><?php echo $pedido['Material']['nome']; ?></td>
                    <td><?php echo $pedido['Material']['barcode']; ?></td>
                    <td><?php echo $pedido['PedidoItem']['aplicacao']; ?></td>
                    <td style="text-align: center;">
                      <label>
                          <input name="form-field-checkbox" class="ace-checkbox-2" type="checkbox" />
                          <span class="lbl"></span>
                        </label>
                    </td>
                </tr>
              <?php } ?>
          </tbody>
          <tfoot>
              <tr>
                  <td colspan="10">
                      <?php echo $this->element('paginacao'); ?>
                  </td>
              </tr>
          </tfoot>
      </table>
  </div>
</div>
