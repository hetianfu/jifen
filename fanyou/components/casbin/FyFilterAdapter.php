<?php
namespace fanyou\components\casbin;

use Casbin\Persist\Adapters\FileFilteredAdapter;

/**
 *
 * @author
 *        
 */
class FyFilterAdapter extends FileFilteredAdapter
{

    public function __construct($fileName)
    {
       parent::__construct(__DIR__ . '/role_'.$fileName.'.csv');
    }


}

