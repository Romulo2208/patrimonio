<div class="login-container">
    <div class="row-fluid">
        <div class="center">
            <?php echo $this->Html->image('logosinpro.png'); ?>
        </div>
    </div>

    <div class="space-6"></div>

    <div class="row-fluid">
        <div class="position-relative">
            <div id="login-box" class="login-box visible widget-box no-border">
                <div class="widget-body" style="padding: 0;">
                    <div class="widget-main">
                        <h4 class="header red lighter bigger">
                            <i class="icon-key"></i>
                            Recriar Senha
                        </h4>

                        <div class="space-6"></div>
                        <p> Voc&ecirc; receber&aacute; uma nova senha em seu email. </p>
                        <?php echo $this->Form->create('Usuario'); ?>
                            <label>
                                <span class="block input-icon input-icon-right">
                                    <input type="email" id="UsuarioLogin" name="data[Usuario][login]" placeholder="Email utilizado para login" class="span12" required="required" />
                                    <i class="icon-envelope"></i>
                                </span>
                            </label>

                            <div class="clearfix">
                                <button  type="submit" class="width-35 pull-right btn btn-small btn-danger">
                                    <i class="icon-envelope"></i>
                                    Enviar
                                </button>
                            </div>
                        <?php echo $this->Form->end(); ?>
                    </div>

                    <div class="toolbar clearfix">
                        <div>
                            <a href="./login"> Voltar para o login </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>