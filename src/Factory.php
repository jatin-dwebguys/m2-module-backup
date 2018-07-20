<?php

namespace Itonomy\Backup;

class Factory extends \Magento\Framework\Backup\Factory
{
    /**
     * Backup type constant for database backup
     */
    const TYPE_DB_COMPRESSED = 'db.gz';

    /**
     * Factory constructor.
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        parent::__construct($objectManager);

        $this->_allowedTypes = [
            self::TYPE_DB,
            self::TYPE_DB_COMPRESSED,
            self::TYPE_FILESYSTEM,
            self::TYPE_SYSTEM_SNAPSHOT,
            self::TYPE_MEDIA,
            self::TYPE_SNAPSHOT_WITHOUT_MEDIA,
        ];
    }
}