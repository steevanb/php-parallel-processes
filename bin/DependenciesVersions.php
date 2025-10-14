<?php

declare(strict_types=1);

use Steevanb\PhpCollection\ScalarCollection\StringCollection;

// phpcs:ignore
class DependenciesVersions
{
    private StringCollection $phpVersions;

    private StringCollection $symfonyVersions;

    private StringCollection $filteredArgv;

    public function __construct(array $argv)
    {
        $this->phpVersions = new StringCollection();
        $this->symfonyVersions = new StringCollection();
        $this->filteredArgv = new StringCollection();

        foreach ($argv as $arg) {
            if (str_starts_with($arg, '--php=')) {
                $this->phpVersions->add(substr($arg, 6));
            } elseif (str_starts_with($arg, '--symfony=')) {
                $this->symfonyVersions->add(substr($arg, 10));
            } else {
                $this->filteredArgv->add($arg);
            }
        }

        if ($this->phpVersions->count() === 0) {
            $this
                ->phpVersions
                ->add('8.2')
                ->add('8.3')
                ->add('8.4')
                ->setReadOnly();
        }

        if ($this->symfonyVersions->count() === 0) {
            $this
                ->symfonyVersions
                ->add('7.0')
                ->add('7.1')
                ->add('7.2')
                ->add('7.3')
                ->setReadOnly();
        }
    }

    public function getPhpVersions(): StringCollection
    {
        return $this->phpVersions;
    }

    public function getSymfonyVersions(): StringCollection
    {
        return $this->symfonyVersions;
    }

    public function getFilteredArgv(): StringCollection
    {
        return $this->filteredArgv;
    }
}
