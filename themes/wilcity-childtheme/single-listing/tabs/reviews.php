<?php global $post; ?>
<wil-single-nav-wrapper v-if="currentTab === 'reviews'" :settings="getNavigation('reviews')" id="single-nav-reviews">
    <div class="content-box_body__3tSRB">
        <?php ic_reviews() ?>
    </div>
</wil-single-nav-wrapper>
