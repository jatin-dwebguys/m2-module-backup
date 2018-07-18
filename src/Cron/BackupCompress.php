<?php

namespace Itonomy\Backup\Cron;

use Magento\Store\Model\ScopeInterface;

class BackupCompress
{
    const XML_PATH_BACKUP_ENABLED = 'system/backup/enabled';

    /**
     * Error messages
     *
     * @var array
     */
    protected $_errors = [];

    /**
     * BackupCleanup constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Itonomy\Backup\Model\BackupProcessor $backupProcessor
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Itonomy\Backup\Model\BackupProcessor $backupProcessor
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_backupProcessor = $backupProcessor;
    }

    /**
     * Create Backup
     *
     * @return $this
     * @throws \Exception
     */
    public function execute()
    {
        if (!$this->_scopeConfig->isSetFlag(self::XML_PATH_BACKUP_ENABLED, ScopeInterface::SCOPE_STORE)) {
            return $this;
        }

        $this->_errors = [];
        try {
            $this->_backupProcessor->compressBackups();
        } catch (\Exception $e) {
            $this->_errors[] = $e->getMessage();
            $this->_errors[] = $e->getTrace();
            throw $e;
        }

        return $this;
    }
}
