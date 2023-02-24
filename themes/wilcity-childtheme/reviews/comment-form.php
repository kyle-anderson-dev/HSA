<?php
global $post, $wilcityReviewConfiguration;
if ( !isset($wilcityReviewConfiguration['enableReview']) || !$wilcityReviewConfiguration['enableReview'] ){
    return '';
}
?>
<?php ic_reviews() ?>

