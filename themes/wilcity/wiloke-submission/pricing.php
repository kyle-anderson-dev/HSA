<?php
/*
 * Template Name: Wilcity Package Page
 */

ob_start();
get_header();

use WilokeListingTools\Frontend\User as WilokeUser;
use WilokeListingTools\Framework\Helpers\GetWilokeSubmission;
use WilokeListingTools\Framework\Helpers\GetSettings;
use WilokeListingTools\Models\UserModel;
use WilokeListingTools\Framework\Helpers\DebugStatus;
use WilokeListingTools\Framework\Helpers\Submission;
use WilokeListingTools\Frontend\SingleListing;

global $wiloke;
?>
    <div class="wil-content">
        <section class="wil-section bg-color-gray-2">
            <div class="container">
                <div class="row" data-col-xs-gap="20">
                    <?php
                    if (is_user_logged_in()) {
                        if (!\WilokeListingTools\Frontend\User::canSubmitListing()) {
                            if (GetWilokeSubmission::getField('toggle_become_an_author') == 'enable' &&
                                $becomeAnAuthorUrl = GetWilokeSubmission::getField('become_an_author_page', true)) {
                                wp_safe_redirect($becomeAnAuthorUrl);
                                exit();
                            }
                        } else {
                            if (have_posts()) {
                                while (have_posts()) {
                                    the_post();
                                    the_content();
                                }
                            }
                        }
                    } else {
                        do_action('wilcity/can-not-submit-listing');
                    }
                    ?>
                </div>
            </div>
        </section>
    </div>
<?php
do_action('wilcity/before-close-root');
get_footer();
$content = ob_get_contents();
ob_end_clean();

echo $content;