<?php

namespace WilokeListingTools\Models;

use WilokeListingTools\Framework\Helpers\General;
use WilokeListingTools\Framework\Helpers\GetSettings;

class PostModel
{
    public static function updatePostStatus($postId, $postStatus): bool
    {
        global $wpdb;

        $status = $wpdb->update(
            $wpdb->posts,
            [
                'post_status' => $postStatus
            ],
            [
                'ID' => $postId
            ],
            [
                '%s'
            ],
            [
                '%d'
            ]
        );

        if ($status) {
            clean_post_cache($postId);
            return true;
        }

        return false;
    }

    /*
     * @postID: int
     * @menuOrder: int
     */
    public static function setMenuOrder($postID, $menuOrder)
    {
        global $wpdb;
        $wpdb->update(
            $wpdb->posts,
            [
                'menu_order' => $menuOrder
            ],
            [
                'ID' => $postID
            ],
            [
                '%d'
            ],
            [
                '%d'
            ]
        );
    }

    public static function getLastListingIDByBelongsToPlanID($planID)
    {
        global $wpdb;

        $aPostTypes = General::getPostTypeKeys(true, false);

        foreach ($aPostTypes as $key => $postID) {
            $aPostTypes[$key] = $wpdb->_real_escape($postID);
        }

        $postID = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT $wpdb->postmeta.post_id FROM $wpdb->postmeta LEFT JOIN $wpdb->posts ON ($wpdb->postmeta.post_id = $wpdb->posts.ID)  WHERE $wpdb->postmeta.meta_key='wilcity_belongs_to' AND $wpdb->postmeta.meta_value=%d AND $wpdb->posts.post_type IN ('" .
                implode("','", $aPostTypes) . "') ORDER BY $wpdb->postmeta.meta_id DESC",
                $planID
            )
        );

        return empty($postID) ? false : $postID;
    }

    public static function countAllPosts()
    {
        $aPostKeys = General::getPostTypeKeys(false, true);
        $totalPosts = 0;

        foreach ($aPostKeys as $postType) {
            $oCountPosts = wp_count_posts($postType);
            if (isset($oCountPosts->publish)) {
                $totalPosts += $oCountPosts->publish;
            }
        }

        return $totalPosts;
    }

    public static function isClaimed($postID = "")
    {
        if (empty($postID)) {
            global $post;
            if (empty($post)) {
                return false;
            }

            $postID = $post->ID;
        }
        $claimStatus = GetSettings::getPostMeta($postID, 'claim_status');

        return $claimStatus == 'claimed';
    }
}
