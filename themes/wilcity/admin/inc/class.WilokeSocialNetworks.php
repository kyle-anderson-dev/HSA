<?php
if (!defined('ABSPATH')) {
    exit;
}

class WilokeSocialNetworks
{
    static public $aSocialNetworks
        = [
            'facebook',
            'twitter',
            'google-plus',
            'tumblr',
            'vk',
            'odnoklassniki',
            'youtube',
            'vimeo',
            'rutube',
            'dribbble',
            'instagram',
            'flickr',
            'pinterest',
            'medium',
            'tripadvisor',
            'wikipedia',
            'stumbleupon',
            'livejournal',
            'linkedin',
            'skype',
            'bloglovin',
            'whatsapp',
            'soundcloud',
            'line',
            'spotify',
            'strava',
            'yelp',
            'snapchat',
            'telegram',
            'tiktok',
            'discord',

        ];
    static public $aSocialNetworksFull
        = [
            'facebook'      => [
                'label' => 'Facebook',
                'id'    => 'facebook',
                'icon'  => ''
            ],
            'twitter'       => [
                'label' => 'Twitter',
                'id'    => 'twitter',
                'icon'  => ''
            ],
            'tumblr'        => [
                'label' => 'Tumblr',
                'id'    => 'tumblr',
                'icon'  => ''
            ],
            'vk'            => [
                'label' => 'VK',
                'id'    => 'vk',
                'icon'  => ''
            ],
            'odnoklassniki' => [
                'label' => 'Odnoklassniki',
                'id'    => 'odnoklassniki',
                'icon'  => ''
            ],
            'youtube'       => [
                'label' => 'Youtube',
                'id'    => 'youtube',
                'icon'  => ''
            ],
            'vimeo'         => [
                'label' => 'Vimeo',
                'id'    => 'vimeo',
                'icon'  => ''
            ],
            'rutube'        => [
                'label' => 'Rutube',
                'id'    => 'rutube',
                'icon'  => ''
            ],
            'dribbble'      => [
                'label' => 'Dribbble',
                'id'    => 'dribbble',
                'icon'  => ''
            ],
            'instagram'     => [
                'label' => 'Instagram',
                'id'    => 'instagram',
                'icon'  => ''
            ],
            'flickr'        => [
                'label' => 'Flickr',
                'id'    => 'flickr',
                'icon'  => ''
            ],
            'pinterest'     => [
                'label' => 'Pinterest',
                'id'    => 'pinterest',
                'icon'  => ''
            ],
            'medium'        => [
                'label' => 'Medium',
                'id'    => 'medium',
                'icon'  => ''
            ],
            'tripadvisor'   => [
                'label' => 'Tripadvisor',
                'id'    => 'tripadvisor',
                'icon'  => ''
            ],
            'wikipedia'     => [
                'label' => 'Wikipedia',
                'id'    => 'wikipedia',
                'icon'  => ''
            ],
            'stumbleupon'   => [
                'label' => 'Stumbleupon',
                'id'    => 'stumbleupon',
                'icon'  => ''
            ],
            'livejournal'   => [
                'label' => 'Livejournal',
                'id'    => 'livejournal',
                'icon'  => ''
            ],
            'linkedin'      => [
                'label' => 'Linkedin',
                'id'    => 'linkedin',
                'icon'  => ''
            ],
            'skype'         => [
                'label' => 'Skype',
                'id'    => 'skype',
                'icon'  => ''
            ],
            'bloglovin'     => [
                'label' => 'Bloglovin',
                'id'    => 'bloglovin',
                'icon'  => ''
            ],
            'whatsapp'      => [
                'label' => 'Whatsapp',
                'id'    => 'whatsapp',
                'icon'  => ''
            ],
            'soundcloud'    => [
                'label' => 'Soundcloud',
                'id'    => 'soundcloud',
                'icon'  => ''
            ],
            'line'          => [
                'label' => 'Line',
                'id'    => 'line',
                'icon'  => ''
            ],
            'spotify'       => [
                'label' => 'Spotify',
                'id'    => 'spotify',
                'icon'  => ''
            ],
            'yelp'       => [
                'label' => 'Yelp',
                'id'    => 'yelp',
                'icon'  => ''
            ],
            'snapchat'       => [
                'label' => 'Snapchat',
                'id'    => 'snapchat',
                'icon'  => ''
            ],
            'telegram'       => [
                'label' => 'Telegram',
                'id'    => 'telegram',
                'icon'  => ''
            ],
            'tiktok'       => [
                'label' => 'Tiktok',
                'id'    => 'tiktok',
                'icon'  => ''
            ],
            'discord'       => [
                'label' => 'Discord',
                'id'    => 'discord',
                'icon'  => ''
            ],
            'strava'       => [
                'label' => 'Strava',
                'id'    => 'strava',
                'icon'  => ''
            ],
        ];

    function __constructor()
    {
    }

    static public function createMetaboxConfiguration($prefix)
    {
        $aSettings = [];

        foreach (self::$aSocialNetworks as $social) {
            if ($social == 'google-plus') {
                $name = 'Google+';
            } else {
                $name = ucfirst($social);
            }

            $aSettings[] = [
                'name' => $name,
                'id'   => $prefix . $social,
                'type' => 'text_url'
            ];
        }

        return $aSettings;
    }

    static function getExcludeNetworks()
    {
        $aThemeOptions = Wiloke::getThemeOptions(true);
        if (!isset($aThemeOptions['wiloke_exclude_social_networks']) ||
            empty($aThemeOptions['wiloke_exclude_social_networks'])) {
            return false;
        }

        $aParse = explode(',', $aThemeOptions['wiloke_exclude_social_networks']);

        return array_map(function ($val) {
            return strtolower(trim($val));
        }, $aParse);
    }

    public static function getPickupSocialOptions()
    {
        $aExcludes = self::getExcludeNetworks();
        if (empty($aExcludes)) {
            return array_values(self::$aSocialNetworksFull);
        }

        foreach ($aExcludes as $social) {
            unset(self::$aSocialNetworksFull[$social]);
        }

        return array_values(self::$aSocialNetworksFull);
    }

    static function getUsedSocialNetworks()
    {
        $aExclude = self::getExcludeNetworks();
        self::$aSocialNetworks = apply_filters('wilcity/filter/social/networks', self::$aSocialNetworks);
        if (!empty($aExclude)) {
            self::$aSocialNetworks = array_diff(self::$aSocialNetworks, $aExclude);
        }

        return self::$aSocialNetworks;
    }

    static public function render_setting_field()
    {
        $aSocials = [];

        $aSocials[] = [
            'id'          => 'wiloke_exclude_social_networks',
            'type'        => 'text',
            'title'       => 'Exclude Social Networks',
            'subtitle'    => 'The social networks that are listed in this field will not be displayed on the front-page.',
            'description' => 'Each social network is seperated by a comma. For example: facebook,twitter,google-plus.',
            'default'     => ''
        ];

        self::getUsedSocialNetworks();

        foreach (self::$aSocialNetworks as $key) {
            if ($key == 'google-plus') {
                $socialName = 'Google+';
            } else {
                $socialName = ucfirst($key);
            }
            $key = 'social_network_' . $key;

            $aSocials[] = [
                'id'       => $key,
                'type'     => 'text',
                'title'    => $socialName,
                'subtitle' => esc_html__('Social icon will not display if you leave empty', 'wilcity'),
                'default'  => ''
            ];
        }

        return $aSocials;
    }

    static public function render_socials($aData, $separated = '')
    {
        global $wiloke;
        if (empty($aData)) {
            return;
        }

        if (!empty($separated)) {
            ob_start();
        }

        foreach (self::$aSocialNetworks as $key) {
            $icon = $key;
            if ($icon == 'bloglovin') {
                $icon = 'heart';
            }

            $socialIcon = 'fa fa-' . str_replace('_', '-', $icon);

            $key = 'social_network_' . $key;
            if (isset($wiloke->aThemeOptions[$key]) && !empty($wiloke->aThemeOptions[$key])) {
                $separated = isset($last) && $last == $key ? '' : $separated;
                do_action('wiloke_hook_before_render_social_network');
                if (has_filter('wiloke_filter_social_network')) {
                    echo apply_filters('wiloke_filter_social_network', $wiloke->aThemeOptions[$key], $socialIcon,
                        $separated);
                } else {
                    ?>
                <a class="<?php echo esc_attr($aData['linkClass']); ?>"
                   href="<?php echo esc_url($wiloke->aThemeOptions[$key]); ?>"
                   rel="noopener"
                   rel="noreferrer"
                   target="_blank"><i class="<?php echo esc_attr($socialIcon); ?>"></i>
                    </a><?php echo esc_html($separated); ?>
                    <?php
                }
                do_action('wiloke_hook_after_render_social_network');
            }
        }

        if (!empty($separated)) {
            $content = ob_get_contents();
            ob_end_clean();
            $content = rtrim($content, $separated);
            Wiloke::ksesHTML($content);
        }
    }
}

new WilokeSocialNetworks();
