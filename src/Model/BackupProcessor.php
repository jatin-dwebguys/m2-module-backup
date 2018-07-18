<?php

namespace Itonomy\Backup\Model;

use Itonomy\Backup\Model\Backup;
use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ObjectManagerFactory;
use Magento\Framework\Backup\Factory;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Setup\BackupRollback;
use Symfony\Component\Console\Output\OutputInterface;

class BackupProcessor
{
    /**
     * @var ObjectManagerFactory
     */
    private $objectManagerFactory;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * File
     *
     * @var File
     */
    private $file;

    /**
     * Filesystem Directory List
     *
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * @param DirectoryList $directoryList
     * @param File $file
     */
    public function __construct(
        File $file,
        DirectoryList $directoryList,
        ObjectManagerFactory $objectManagerFactory
    ) {
        $this->file = $file;
        $this->directoryList = $directoryList;
        $this->objectManagerFactory = $objectManagerFactory;
    }

    /**
     * @param null $output
     * @return array|void
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function cleanupBackups($output = null)
    {
        $oldFiles = [];
        $backupsDir = $this->getBackupDirectory();
        if ($this->file->isExists($backupsDir)) {
            $contents = $this->file->readDirectoryRecursively($backupsDir);

            array_multisort(
                array_map('filemtime', $contents),
                SORT_NUMERIC,
                SORT_DESC,
                $contents
            );

            foreach ($contents as $path) {
                $partsOfPath = explode('/', str_replace('\\', '/', $path));
                $fileName = $partsOfPath[count($partsOfPath) - 1];
                // if filename starts with '.' skip, e.g. '.git'
                if (!(strpos($fileName, '.') === 0)) {
                    $nameWithoutExtension = explode('.', $fileName);
                    // actually first part of $filenameWithoutExtension contains only the filename without extension
                    // and filename contains the type of backup separated by '_'
                    $fileNameParts = explode('_', $nameWithoutExtension[0]);

                    if (in_array(Factory::TYPE_DB, $fileNameParts)) {
                        $time=filemtime($backupsDir.'/'.$fileName);
                        $oldFiles[$fileName] = $time;

                        $deleteFiles = array_splice($oldFiles, 2);
                        if (count($contents) > 3) {
                            foreach ($deleteFiles as $file => $stamp) {
                                try {
                                    $this->getBackup()->load($file, $this->getBackupDirectory())->deleteFile();
                                } catch (\Exception $e) {
                                    $this->writeLog(
                                        printf("File %s, %s : %s", $file, $stamp, $e->getMessage()),
                                        $output
                                    );
                                }
                            }
                        }
                    }
                }
            }
            if (empty($oldFiles)) {
                $this->writeLog('<info>No backup files found.</info>', $output);
                return;
            }

            $this->writeLog("<info>Showing backup files in $backupsDir.</info>", $output);
            /** @var \Symfony\Component\Console\Helper\Table $table */

            return $oldFiles;
        } else {
            $this->writeLog('<info>No backup files found.</info>', $output);
        }
    }

    /**
     * @param null $output
     * @return array|void
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function compressBackups($output = null)
    {
        $files = [];
        $backupsDir = $this->getBackupDirectory();
        if ($this->file->isExists($backupsDir)) {
            $contents = $this->file->readDirectoryRecursively($backupsDir);

            array_multisort(
                array_map('filemtime', $contents),
                SORT_NUMERIC,
                SORT_DESC,
                $contents
            );

            foreach ($contents as $path) {
                $partsOfPath = explode('/', str_replace('\\', '/', $path));
                $fileName = $partsOfPath[count($partsOfPath) - 1];
                // if filename starts with '.' skip, e.g. '.git'
                if (!(strpos($fileName, '.') === 0)) {
                    $nameWithoutExtension = explode('.', $fileName);
                    // actually first part of $filenameWithoutExtension contains only the filename without extension
                    // and filename contains the type of backup separated by '_'
                    $fileNameParts = explode('_', $nameWithoutExtension[0]);

                    if (in_array(Factory::TYPE_DB, $fileNameParts) && $nameWithoutExtension[1] !== '.sql.gz') {
                        $this->gzCompressFile($backupsDir.'/'.$fileName, 7);
                        $files[$fileName] = $backupsDir.'/'.$fileName.".gz";

                    }
                }
            }
            if (empty($files)) {
                $this->writeLog('<info>No backup files found.</info>', $output);
                return;
            }

            $this->writeLog("<info>Showing backup files in $backupsDir.</info>", $output);
            /** @var \Symfony\Component\Console\Helper\Table $table */

            return $files;
        } else {
            $this->writeLog('<info>No backup files found.</info>', $output);
        }
    }

    /**
     * Gets initialized object manager
     *
     * @return ObjectManagerInterface
     */
    protected function getObjectManager()
    {
        if (null == $this->objectManager) {
            $area = FrontNameResolver::AREA_CODE;
            $this->objectManager = $this->objectManagerFactory->create($_SERVER);
            /** @var \Magento\Framework\App\State $appState */
            $appState = $this->objectManager->get(\Magento\Framework\App\State::class);
            $appState->setAreaCode($area);
            $configLoader = $this->objectManager->get(\Magento\Framework\ObjectManager\ConfigLoaderInterface::class);
            $this->objectManager->configure($configLoader->load($area));
        }
        return $this->objectManager;
    }

    /**
     * @param $source
     * @param int $level
     * @return bool|string
     */
    protected function gzCompressFile($source, $level = 9)
    {
        $dest = $source . '.gz';
        $mode = 'wb' . $level;
        $error = false;
        if ($fpOut = gzopen($dest, $mode)) {
            if ($fpIn = fopen($source, 'rb')) {
                while (!feof($fpIn)) {
                    gzwrite($fpOut, fread($fpIn, 1024 * 512));
                }
                fclose($fpIn);
            } else {
                $error = true;
            }

            gzclose($fpOut);
        } else {
            $error = true;
        }

        if ($error) {
            return false;
        }
        return $dest;
    }

    /**
     * @return Backup
     */
    protected function getBackup()
    {
        return $this->getObjectManager()->get(Backup::class);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function getBackupDirectory()
    {
        return $this->directoryList->getPath(DirectoryList::VAR_DIR) . '/' . BackupRollback::DEFAULT_BACKUP_DIRECTORY;
    }

    /**
     * @param $message
     * @param $output
     */
    protected function writeLog($message, $output = null)
    {
        if (!is_null($output) && $output instanceof OutputInterface) {
            $output->writeln($message);
        }

        return;
    }
}
