<?php
/**
 * Issue comment loop
 *
 *
 */
$authors    = array();
foreach($comments as $k => $c):
    $author   = $c->createdByUserID;
    $rndColor = sprintf("%02X%02X%02X", mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    
    // Add author
    if( ! in_array($author, array_keys($authors)) ):
        $authors[$author] = $rndColor;
        
        // Ensure color is unique
        while( in_array($rndColor, array_values($authors)) ):
            $rndColor = sprintf("%02X%02X%02X", mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
        endwhile;
    endif;
    
    // Add author color
    $color = $authors[$author];
?>
    <article class="issueCommentContainer"
             style="border: 2px solid #<?php echo $color;?>">
        <figure class="issueCommentAuthorIcon"
                style="background-color: #<?php echo $color;?>">
            <?php //echo $c->createdByLogin;?>
        </figure>
        
        <div class="commentDetails">
            <p class="commentText">
                <?php echo $c->text;?>
            </p>
            
            by <a href="/user/<?php echo $c->createdByUserID;?>"><?php echo $c->createdByLogin;?></a>
            
            <abbr class="issueCommentTimestamp" 
                  title="<?php echo $c->createdAt;?>"><?php echo $c->createdAt;?></abbr>
        </div>
    </article>

<?php
endforeach;
