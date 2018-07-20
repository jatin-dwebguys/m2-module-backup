<?php

namespace Itonomy\Backup\Helper;

use Itonomy\Backup\Factory;

class Data extends \Magento\Backup\Helper\Data
{
    /**
     * Get all possible backup type values with descriptive title
     *
     * @return array
     */
    public function getBackupTypes()
    {
        return [
            Factory::TYPE_DB              => __('Database'),
            Factory::TYPE_DB_COMPRESSED   => __('Database compressed'),
            Factory::TYPE_MEDIA           => __('Database and Media'),
            Factory::TYPE_SYSTEM_SNAPSHOT => __('System'),
            Factory::TYPE_SNAPSHOT_WITHOUT_MEDIA => __('System (excluding Media)')
        ];
    }

    /**
     * Get all possible backup type values
     *
     * @return string[]
     */
    public function getBackupTypesList()
    {
        return [
            Factory::TYPE_DB,
            Factory::TYPE_DB_COMPRESSED,
            Factory::TYPE_SYSTEM_SNAPSHOT,
            Factory::TYPE_SNAPSHOT_WITHOUT_MEDIA,
            Factory::TYPE_MEDIA
        ];
    }

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
            Factory::TYPE_DB_COMPRESSED => 'sql.gz',
            Factory::TYPE_DB => 'sql'
        ];
    }
}