<?php
foreach($comments as $k => $c):
?>
   
    <article class="issueCommentContainer">
        <figure class="issueCommentAuthorIcon"
                style="background-color: #<?php echo sprintf("%02X%02X%02X", mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));?>">
            <?php //echo $c->createdByLogin;?>
        </figure>
        
        <div class="commentDetails">
            <p>
                <?php echo $c->text;?>
            </p>
            
            by <a href="/user/<?php echo $c->createdByUserID;?>"><?php echo $c->createdByLogin;?></a>
            
            <abbr class="issueCommentTimestamp" 
                  title="<?php echo $c->createdAt;?>"><?php echo $c->createdAt;?></abbr>
        </div>
    </article>

<?php
endforeach;
