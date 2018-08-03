<?php

namespace Itonomy\Backup\Helper;

use Itonomy\Backup\Factory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\MaintenanceMode;
use Magento\Framework\Filesystem;
use Magento\Backup\Helper\Data as Helper;

class Data extends Helper
{
    /**
     * Get all types to extensions map
     *
     * @return array
     */
    public function getExtensions()
    {
        return [
            Factory::TYPE_SYSTEM_SNAPSHOT => 'tgz',
            Factory::TYPE_SNAPSHOT_WITHOUT_MEDIA => 'tgz',
            Factory::TYPE_MEDIA => 'tgz',
            Factory::TYPE_DB => 'sql',
            Factory::TYPE_DB_COMPRESSED => 'sql.gz'
        ];
    }
}
