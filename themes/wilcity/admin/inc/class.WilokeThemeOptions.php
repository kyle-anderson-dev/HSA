<?php
/**
 * WilokeThemeOptions Class
 *
 * @category Theme Options
 * @package  Wiloke Framework
 * @author   Wiloke Team
 * @version  1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class WilokeThemeOptions
{
    /**
     * @var string The key of theme options
     */
    protected $_key = 'wiloke_themeoptions';
    public $aArgs = [];
    public $aSections = [];

    public function update_theme_options()
    {
        if (!get_option(Wiloke::$firsTimeInstallation) && !get_option($this->_key)) {
            global $wiloke;
            $aOptions = [];
            foreach ($wiloke->aConfigs['themeoptions']['redux']['sections'] as $aSections) {
                foreach ($aSections['fields'] as $aFields) {
                    $aOptions[$aFields['id']] = isset($aFields['default']) ? $aFields['default'] : '';
                }
            }

            update_option($this->_key, $aOptions);
        }
    }

    public static function getColor($key, $default = '')
    {
        global $wiloke;
        if (!isset($wiloke->aThemeOptions[$key])) {
            return $default;
        }

        if (!isset($wiloke->aThemeOptions[$key]['rgba'])) {
            return $default;
        }

        return $wiloke->aThemeOptions[$key]['rgba'];
    }

    public static function getOptionDetail($key, $default = '')
    {
        global $wiloke;

        if (empty($wiloke->aThemeOptions)) {
            $wiloke->aThemeOptions = get_option(self::keyOption());
        }

        if (isset($wiloke->aThemeOptions[$key])) {

            return $wiloke->aThemeOptions[$key];
        }

        return $default;
    }

    /**
     * @param        $key
     * @param string $size
     * @param string $thumbnailUrl default url
     *
     * @return false|mixed|string
     */
    public static function getThumbnailUrl($key, $size = 'thumbnail', $thumbnailUrl = '')
    {
        $aGeneralThumbnail = WilokeThemeOptions::getOptionDetail($key);

        if (is_array($aGeneralThumbnail)) {
            if (isset($aGeneralThumbnail['id'])) {
                $thumbnailUrl = get_the_post_thumbnail_url($aGeneralThumbnail['id'], $size);
            }

            if (empty($thumbnailUrl)) {
                if (isset($aGeneralThumbnail['thumbnail']) && !empty($aGeneralThumbnail['thumbnail'])) {
                    $thumbnailUrl = $aGeneralThumbnail['thumbnail'];
                }
            }
        }

        return $thumbnailUrl;
    }

    public static function keyOption()
    {
        return 'wiloke_themeoptions';
    }

    /**
     * Get Options. Ensures this function only run if it's front-page.
     */
    public function get_option()
    {
        global $wiloke;
        if (!$wiloke->kindofrequest() || $wiloke->kindofrequest('widgets.php') || $wiloke->kindofrequest('post.php')) {
            $wiloke->aThemeOptions = get_option($this->_key);

            if (!$wiloke->aThemeOptions) {
                return false;
            }

            return $wiloke->aThemeOptions;
        }
    }

    /**
     * Rendering Theme Options
     */
    public function render()
    {
        global $wiloke;

        try {
            if ($wiloke->isClassExists('ReduxFramework', false)) {
                $this->setArguments();
                $this->setSections();

                new ReduxFramework($this->aSections, $this->aArgs);
            }
        } catch (Exception $e) {
            $message = $e->getMessage();

            if ($message) {
                Wiloke::$aErrors['error'][] = esc_html__('Redux Framework plugin is required.', 'wilcity');
            }
        }
    }

    public function setSections()
    {
        global $wiloke;
        $aSections = $this->addColorPalettes($wiloke->aConfigs['themeoptions']['redux']['sections']);

        $this->aSections = $aSections;
    }

    public function addColorPalettes($aArgs)
    {
        global $wiloke;

        if (!isset($wiloke->aConfigs['general']['color_picker']['palette']) &&
            empty($wiloke->aConfigs['general']['color_picker']['palette'])) {
            return $aArgs;
        }

        if (is_array($aArgs)) {
            if (isset($aArgs['type'])) {
                if ($aArgs['type'] == 'color_rgba') {
                    $aArgs['options']['palette']      = $wiloke->aConfigs['general']['color_picker']['palette'];
                    $aArgs['options']['show_palette'] = true;

                    return $aArgs;
                }
            } else {
                return array_map([$this, 'addColorPalettes'], $aArgs);
            }
        }

        return $aArgs;
    }

    /**
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to:
     * https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */
    public function setArguments()
    {
        global $wiloke;
        $wiloke->aConfigs['themeoptions']['redux']['args']['opt_name'] = $this->_key;
        $this->aArgs                                                   =
            $wiloke->aConfigs['themeoptions']['redux']['args'];
    }

    public static function isEnable($featureKey, $lowerCheckLevel = false): bool
    {
        global $wiloke;

        if (empty($wiloke) || empty($wiloke->aThemeOptions)) {
            $aThemeOptions = Wiloke::getThemeOptions(true);
        } else {
            $aThemeOptions = $wiloke->aThemeOptions;
        }

        if ($lowerCheckLevel) {
            return !isset($aThemeOptions[$featureKey]) || $aThemeOptions[$featureKey] !== 'disable';
        }

        return isset($aThemeOptions[$featureKey]) && $aThemeOptions[$featureKey] == 'enable';
    }
}
