<?php

namespace WilokeListingTools\Controllers;

use WILCITY_SC\SCHelpers;
use WilokeListingTools\Framework\Helpers\General;
use WilokeListingTools\Framework\Helpers\GetSettings;

class GridItemController
{
    protected $aCacheGridItemSettings = [];
    
    public function __construct()
    {
        add_action('wilcity/listing-slider/meta-data', [$this, 'gridItemMetaData'], 10, 2);
        add_filter('wilcity/filter-listing-slider/meta-data', [$this, 'aListingBody'], 10, 2);
    }
    
    protected function getSettings($post)
    {
        if (isset($this->aCacheGridItemSettings[$post->post_type])) {
            return $this->aCacheGridItemSettings[$post->post_type];
        }
        
        if ($post->post_type == 'event') {
            $aSettings = [
                [
                    'type' => 'google_address'
                ],
                [
                    'type' => 'hosted_by'
                ],
                [
                    'type' => 'interested_people'
                ],
                [
                    'type' => 'event_starts_on'
                ]
            ];
        } else {
            $aSettings = GetSettings::getOptions(General::getSingleListingSettingKey('card', $post->post_type), false, true);
        }
        $this->aCacheGridItemSettings[$post->post_type] = $aSettings;
        
        return $aSettings;
    }
    
    public function aListingBody($aListing, $post)
    {
        $aSettings = $this->getSettings($post);
        
        if (empty($aSettings)) {
            return $aListing;
        } else {
            foreach ($aSettings as $aItem) {
                switch ($aItem['type']) {
                    case 'interested_people':
                        $aListing['oBody']['interested'] = SCHelpers::renderInterested($post, [], true);
                        break;
                    case 'event_starts_on':
                        $aListing['oBody']['event_starts_on'] = SCHelpers::renderEventStartsOn($post, [], true);
                        break;
                    case 'hosted_by':
                        $aListing['oBody']['hosted_by'] = SCHelpers::renderHostedBy($post, [], true);
                        break;
                    case 'google_address':
                        $address = SCHelpers::renderAddress($post, [], true);
                        if (!empty($address)) {
                            $aListing['oBody']['google_address'] = SCHelpers::renderAddress($post, [], true);
                        }
                        break;
                    case 'phone':
                        $aPhone = SCHelpers::renderPhone($post, [], true);
                        if (!empty($aPhone['value'])) {
                            $aListing['oBody']['phone'] = $aPhone;
                        }
                        break;
                    case 'email':
                        $aEmail = SCHelpers::renderEmail($post, [], true);
                        if (!empty($aEmail['value'])) {
                            $aListing['oBody']['email'] = $aEmail;
                        }
                        break;
                    case 'website':
                        $aWebsite = SCHelpers::renderWebsite($post, [], true);
                        if (!empty($aWebsite['value'])) {
                            $aListing['oBody']['website'] = $aWebsite;
                        }
                        break;
                    case 'price_range':
                        $aPriceRange = SCHelpers::renderPriceRange($post, [], true);
                        
                        if (!empty($aPriceRange) && !empty($aPriceRange['value']['minimumPrice']) &&
                            !empty($aPriceRange['value']['maximumPrice']) &&
                            $aPriceRange['value']['mode'] != 'nottosay') {
                            $aListing['oBody']['price_range'] = $aPriceRange;
                        }
                        break;
                    case 'single_price':
                        $aSinglePrice = SCHelpers::renderSinglePrice($post, [], true);
                        if (!empty($aSinglePrice)) {
                            $aListing['oBody']['single_price'] = $aSinglePrice;
                        }
                        break;
                    case 'listing_location':
                    case 'listing_category':
                    case 'listing_tag':
                    case 'custom_taxonomy':
                        if ($aItem['type'] == 'custom_taxonomy') {
                            $taxonomy = $aItem['content'];
                        } else {
                            $taxonomy = $aItem['key'];
                        }
                        if (empty($taxonomy)) {
                            break;
                        }
                        $aRawTerms = wp_get_post_terms($post->ID, $taxonomy);
                        if (empty($aRawTerms) || is_wp_error($aRawTerms)) {
                            break;
                        }
                        
                        $aTerms = [];
                        foreach ($aRawTerms as $key => $oTerm) {
                            $aTerms[$key]          = get_object_vars($oTerm);
                            $aTerms[$key]['oIcon'] = \WilokeHelpers::getTermOriginalIcon($oTerm);
                            $aTerms[$key]['link']  = get_term_link($oTerm);
                        }
                        
                        $aListing['oBody']['taxonomy_'.$taxonomy] = $aTerms;
                        break;
                    case 'custom_field':
                        ob_start();
                        SCHelpers::renderCustomField($post, $aItem);
                        $customField = ob_get_contents();
                        ob_end_clean();
                        
                        if (!empty($customField)) {
                            $aListing['oBody']['custom_field'][$aItem['key']] = $customField;
                        }
                        break;
                }
            }
        }
        
        return $aListing;
    }
    
    public function gridItemMetaData($post, $aAtts)
    {
        $aSettings = $this->getSettings($post);
        
        if (empty($aSettings)) {
            SCHelpers::renderAddress($post);
            SCHelpers::renderPhone($post);
        } else {
            foreach ($aSettings as $aItem) {
                switch ($aItem['type']) {
                    case 'google_address':
                        if (isset($aAtts['isSearchNearByMe'])) {
                            $aItem['isSearchNearByMe'] = $aAtts['isSearchNearByMe'];
                        }
                        SCHelpers::renderAddress($post, $aItem);
                        break;
                    case 'phone':
                        SCHelpers::renderPhone($post, $aItem);
                        break;
                    case 'email':
                        SCHelpers::renderEmail($post, $aItem);
                        break;
                    case 'website':
                        SCHelpers::renderWebsite($post, $aItem);
                        break;
                    case 'price_range':
                        SCHelpers::renderPriceRange($post, $aItem);
                        break;
                    case 'single_price':
                        SCHelpers::renderSinglePrice($post, $aItem);
                        break;
                    case 'listing_location':
                    case 'listing_cat':
                    case 'listing_tag':
                    case 'custom_taxonomy':
                        SCHelpers::renderTermsOnCard($post, $aItem);
                        break;
                    case 'social_networks':
                        SCHelpers::renderSingleSocialNetworks($post, $aItem);
                        break;
                    case 'custom_field':
                        SCHelpers::renderCustomField($post, $aItem);
                        break;
                }
            }
        }
    }
}
