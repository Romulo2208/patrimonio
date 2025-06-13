<div style="float: left;">
    <?php
    echo $this->Paginator->counter(array(
        'format' => __('Pagina {:page} de {:pages}, mostrando {:current} de {:count} ({:start} ate {:end})')
    ));
    ?>
</div>

<div class="pagination" style="float: right; margin: 0;">
    <ul>
        <?php
        echo $this->Paginator->prev('<<', array('tag' => 'li'), null, array('tag' => 'li', 'class' => 'disabled myclass'));
        echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentClass' => 'active', 'currentTag' => 'a'));
        echo $this->Paginator->next('>>', array('tag' => 'li'), null, array( 'tag' => 'li', 'class' => 'disabled myclass'));
        ?>
    </ul>
</div>