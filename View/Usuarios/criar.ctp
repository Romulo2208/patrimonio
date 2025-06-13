<div class="loginwrapperinner">
    <div class="usuarios form">
        <?php echo $this->Form->create('Usuario'); ?>
        <input type="hidden" id="UsuarioEmpresa" name="data[Usuario][empresa]" value="2"/>
        <p class="animate2 bounceIn"><input type="text" id="FiliadoCpf" name="data[Filiado][cpf]" placeholder="Informe seu CPF" required="required" /></p>
        <p class="animate3 bounceIn"><input type="text" id="FiliadoNome" name="data[Filiado][nome]" placeholder="Seu nome completo" required="required" /></p>
        <p class="animate4 bounceIn"><input type="email" id="UsuarioLogin" name="data[Usuario][login]" placeholder="Seu email" required="required" /></p>
        <p class="animate5 bounceIn"><input type="password" id="UsuarioSenha" name="data[Usuario][senha]" placeholder="Crie uma senha" required="required" /></p>
        <p class="animate6 bounceIn"><button class="btn btn-warning btn-block">Criar</button></p>
        <p class="animate7 fadeIn" style="text-align: right;"><a href="./login">Voltar para o login</a></p>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#FiliadoCpf').mask('999.999.999-99');
    });
</script>