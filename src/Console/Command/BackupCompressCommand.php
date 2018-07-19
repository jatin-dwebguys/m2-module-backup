<?php

namespace Itonomy\Backup\Console\Command;

use Itonomy\Backup\Model\BackupProcessor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BackupCompressCommand extends Command
{
    /**
     * @var BackupProcessor
     */
    private $backupProcessor;

    /**
     * BackupCleanupCommand constructor.
     * @param BackupProcessor $backupProcessor
     */
    public function __construct(
        BackupProcessor $backupProcessor
    ) {
        $this->backupProcessor = $backupProcessor;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('backups:compress')
            ->setDescription('Cleaning up old backups');

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $oldFiles = $this->backupProcessor->compressBackups($output);

            $table = $this->getHelperSet()->get('table');
            $table->setHeaders(['Backup Filename', 'Backup Type']);

            if (is_array($oldFiles)) {
                asort($oldFiles);
                foreach ($oldFiles as $key => $value) {
                    $table->addRow([$key, $value]);
                }
                $table->render($output);
            }
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }
}
