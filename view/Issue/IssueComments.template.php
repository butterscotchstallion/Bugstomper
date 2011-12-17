<?php
foreach($comments as $k => $c):
?>
   
    <article class="issueCommentContainer">
        <h2 class="issueCommentAuthorIcon">
            <?php echo $c->createdByLogin;?>
        </h2>
        <abbr title="<?php echo $c->createdAt;?>"><?php echo $c->createdAt;?></abbr>
        
        <?php echo $c->text;?>
    </article>

<?php
endforeach;
