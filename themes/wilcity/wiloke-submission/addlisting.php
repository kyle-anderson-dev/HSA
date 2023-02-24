<?php
/*
 * Template Name: Wilcity AddListing
 */

use WilokeListingTools\Controllers\AddListingController;
use \WilokeListingTools\Framework\Helpers\General;

if (class_exists('\WilokeListingTools\Controllers\AddListingController')) {
    AddListingController::saveListingIDToSession();
}

get_header();

use WilokeListingTools\Frontend\User as WilokeUser;
use WilokeListingTools\Framework\Helpers\GetWilokeSubmission;
use WilokeListingTools\Framework\Helpers\Submission;

global $wiloke, $post;
$listingType = isset($_GET['listing_type']) && !empty($_GET['listing_type']) ? $_GET['listing_type'] :
    General::getDefaultPostTypeKey(false, true);
if (!is_user_logged_in()) {
    ?>
    <div class="wil-content">
        <section class="wil-section bg-color-gray-2">
            <div class="container">
                <div class="row" data-col-xs-gap="20">
                    <?php do_action('wilcity/can-not-submit-listing'); ?>
                </div>
            </div>
        </section>
    </div>
    <?php
} else {
    ?>
    <div class="wil-content">
        <section class="wil-section bg-color-gray-2 pt-30">
            <?php do_action('wilcity/wiloke-submission/addlisting/before-container', ['postType' => $listingType]); ?>
            <div class="container">
                <div id="<?php echo esc_attr(apply_filters('wilcity/filter/id-prefix', 'wilcity-addlisting')); ?>"
                     class="wil-addlisting row hidden">
                    <?php
                    if (!WilokeUser::canSubmitListing()) {
                        do_action('wilcity/can-not-submit-listing');
                    } else {
                        $aPostTypeSupported = Submission::getSupportedPostTypes();
                        if (!in_array($listingType, $aPostTypeSupported)) {
                            WilokeMessage::message([
                                'msg'        => sprintf(__('Oops! %s type is not supported.', 'wilcity'),
                                    $_REQUEST['listing_type']),
                                'msgIcon'    => 'la la-bullhorn',
                                'status'     => 'danger',
                                'hasMsgIcon' => true
                            ]);
                        } else {
                            $isAllowedAddListing = true;
                            if (isset($_GET['postID']) && !empty($_GET['postID'])) {
                                if (!current_user_can('administrator') &&
                                    get_post_field('post_author', $_GET['postID'])
                                    != get_current_user_id()) {
                                    $isAllowedAddListing = false;
                                    WilokeMessage::message([
                                        'msg'        => esc_html__('You do not have permission to access this page.',
                                            'wilcity'),
                                        'msgIcon'    => 'la la-bullhorn',
                                        'status'     => 'danger',
                                        'hasMsgIcon' => true
                                    ]);
                                }
                            }

                            if ($isAllowedAddListing):
                                ?>
                                <div class="col-md-4 col-lg-4 md-hide js-sticky">
                                    <?php do_action('wiloke/wilcity/addlisting/print-sidebar-items', $post); ?>
                                </div>
                                <div class="col-md-8 col-lg-8 ">
                                    <?php
                                    do_action('wiloke/wilcity/addlisting/print-fields', $post); ?>
                                </div>
                            <?php
                            endif;
                        }
                    }
                    ?>
                </div>
            </div>
            <?php do_action('wilcity/wiloke-submission/addlisting/after-container', ['postType' => $listingType]); ?>
        </section>
    </div>
    <?php
}
do_action('wilcity/before-close-root');
get_footer();
