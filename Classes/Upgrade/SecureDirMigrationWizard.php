<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2026
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Upgrade;


use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Install\Updates\ChattyInterface;
use TYPO3\CMS\Install\Updates\RepeatableInterface;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;


/**
 * Moves the protected Aimeos files out of the public document root
 *
 * @package TYPO3
 */
class SecureDirMigrationWizard implements UpgradeWizardInterface, RepeatableInterface, ChattyInterface
{
    private ?OutputInterface $output = null;


    public function getIdentifier() : string
    {
        return 'aimeos_secureDirMigration';
    }


    public function getTitle() : string
    {
        return 'Aimeos: Move protected files out of the public directory';
    }


    public function getDescription() : string
    {
        return sprintf(
            'Moves the protected Aimeos files (downloads, imports, exports) from "%1$s" to "%2$s"',
            $this->oldPath(), $this->newPath()
        );
    }


    public function getPrerequisites() : array
    {
        return [];
    }


    public function setOutput(OutputInterface $output) : void
    {
        $this->output = $output;
    }


    public function updateNecessary() : bool
    {
        return is_dir($this->oldPath()) && $this->oldPath() !== $this->newPath();
    }


    public function executeUpdate() : bool
    {
        $old = $this->oldPath();
        $new = $this->newPath();

        if (!is_dir($new) && is_dir(dirname($new)) && @rename($old, $new)) {
            $this->output?->writeln(sprintf('Moved "%1$s" to "%2$s"', $old, $new));
            return true;
        }

        $result = $this->move($old, $new);

        if ($result) {
            $this->output?->writeln(sprintf('Moved files from "%1$s" to "%2$s"', $old, $new));
        }

        return $result;
    }


    /**
     * Moves the content of the old directory recursively into the new one
     *
     * Files already existing in the new directory are not overwritten and the
     * old directory is only removed if all files have been moved.
     *
     * @param string $old Absolute path of the old directory
     * @param string $new Absolute path of the new directory
     * @return bool TRUE if all files have been moved, FALSE if not
     */
    protected function move(string $old, string $new) : bool
    {
        if (!is_dir($new) && !@mkdir($new, 0755, true)) {
            $this->output?->writeln(sprintf('Directory "%1$s" could not be created', $new));
            return false;
        }

        $result = true;

        foreach (scandir($old) ?: [] as $name) {
            if ($name === '.' || $name === '..') {
                continue;
            }

            $src = $old . '/' . $name;
            $dest = $new . '/' . $name;

            if (is_dir($src) && !is_link($src)) {
                $result = $this->move($src, $dest) && $result;
            } elseif (file_exists($dest)) {
                $this->output?->writeln(sprintf('File "%1$s" already exists, keeping "%2$s"', $dest, $src));
                $result = false;
            } elseif (!@rename($src, $dest)) {
                $this->output?->writeln(sprintf('File "%1$s" could not be moved to "%2$s"', $src, $dest));
                $result = false;
            }
        }

        return $result && @rmdir($old);
    }


    protected function newPath() : string
    {
        return Environment::getVarPath() . '/.secure';
    }


    protected function oldPath() : string
    {
        return Environment::getPublicPath() . '/uploads/tx_aimeos/.secure';
    }
}
