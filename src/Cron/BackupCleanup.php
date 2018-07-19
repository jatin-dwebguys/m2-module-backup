<?php

namespace Itonomy\Backup\Cron;

use Magento\Store\Model\ScopeInterface;

class BackupCleanup
{
    const XML_PATH_BACKUP_ENABLED = 'system/backup/enabled';

    /**
     * Error messages
     *
     * @var array
     */
    protected $errors = [];

    /**
     * BackupCleanup constructor.
     * @param \Itonomy\Backup\Model\BackupProcessor $processor
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Itonomy\Backup\Model\BackupProcessor $processor,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_processor = $processor;
        $this->_scopeConfig = $scopeConfig;
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

        $this->errors = [];
        try {
            $this->cleanBackups->cleanupBackups();
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            $this->errors[] = $e->getTrace();
            throw $e;
        }

        return $this;
    }
}
