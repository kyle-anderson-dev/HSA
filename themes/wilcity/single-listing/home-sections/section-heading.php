<?php
global $wilcityArgs;
if (isset($wilcityArgs['isShowBoxTitle']) && $wilcityArgs['isShowBoxTitle'] === 'no') {
    return '';
}

$oHeading = new \Wilcity\Ultils\ListItems\Heading();
$oHeading = $oHeading->setConfiguration([
    'type'           => 'heading',
    'wrapperClasses' => 'content-box_header__xPnGx clearfix',
    'icon'           => $wilcityArgs['icon'],
    'heading'        => $wilcityArgs['name'],
    'headingTag'     => 'h2'
]);

try {
    echo $oHeading->render();
} catch (Exception $e) {
}
