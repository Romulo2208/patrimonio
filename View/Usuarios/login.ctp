<div class="login-container">
    <div class="row-fluid">
        <div class="center">
            <?php echo $this->Html->image('logobritacal3.png'); ?>
        </div>
    </div>

    <div class="space-6"></div>

    <div class="row-fluid">
        <div class="position-relative">
            <div id="login-box" class="login-box visible widget-box no-border">
                <div class="widget-body" style="padding: 0;">
                    <div class="widget-main">
                        <h4 class="header lighter bigger">
                            <i class="icon-globe"></i>
                            ALMOXARIFADO
                        </h4>
                        <div class="space-6"></div>
                        <?php echo $this->Form->create('Usuario'); ?>
                            <label>
                                <span class="block input-icon input-icon-right">
                                    <input type="email" id="UsuarioLogin" class="span12" name="data[Usuario][login]" placeholder="Email" required="required" />
                                    <i class="icon-user"></i>
                                </span>
                            </label>

                            <label>
                                <span class="block input-icon input-icon-right">
                                    <input type="password" id="UsuarioSenha" class="span12" name="data[Usuario][senha]" placeholder="Senha" required="required" />
                                    <i class="icon-lock"></i>
                                </span>
                            </label>

                            <div class="space"></div>
                            <div class="clearfix">
                                <button type="submit" class="width-35 pull-right btn btn-small btn-primary">
                                    <i class="icon-key"></i>
                                    Entrar
                                </button>
                            </div>
                            <div class="space-4"></div>
                        <?php echo $this->Form->end(); ?>
                    </div>

                    <div class="toolbar clearfix">
                        <div>
                            <a href="./esqueci"> Esqueci minha senha </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
