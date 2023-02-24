<?php

namespace WilokeListingTools\Controllers\Retrieve;

use WilokeListingTools\Controllers\RetrieveController;

class NormalRetrieve implements RetrieveInterface
{
    /**
     * @param array $aData
     *
     * @return array
     */
    public function success($aData = [])
    {
        $aData['status'] = 'success';
        return $aData;
    }

    /**
     * @param array $aData
     *
     * @return array
     */
    public function error($aData = [])
    {
        $aData['status'] = 'error';
        return $aData;
    }
}
