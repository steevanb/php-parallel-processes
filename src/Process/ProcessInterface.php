<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Process;

interface ProcessInterface
{
    public function isTerminated(): bool;

    public function isSuccessful(): bool;

    public function getOutput(): string;

    public function getErrorOutput(): string;

    public function isStarted(): bool;

    public function isRunning(): bool;

    public function getStatus(): string;

    public function setName(string $name): static;

    public function getName(): string;

    public function getOutputStatePrefix(): ?string;

    public function getOutputSummaryPrefix(): ?string;

    public function setStandardOutputVerbosity(int $standardOutputVerbosity): static;

    public function getStandardOutputVerbosity(): int;

    public function setErrorOutputVerbosity(int $errorOutputVerbosity): static;

    public function getErrorOutputVerbosity(): int;

    public function setFailureStandardOutputVerbosity(int $failureStandardOutputVerbosity): static;

    public function getFailureStandardOutputVerbosity(): int;

    public function setFailureErrorOutputVerbosity(int $failureErrorOutputVerbosity): static;

    public function getFailureErrorOutputVerbosity(): int;

    public function setCanceledOutputVerbosity(int $canceledOutputVerbosity): static;

    public function getCanceledOutputVerbosity(): int;

    public function getExecutionTime(): int;

    public function getStartCondition(): StartCondition;

    public function setCanceled(bool $canceled = true): static;

    public function isCanceled(): bool;

    public function setCanceledAsError(bool $canceledAsError = true): static;

    public function isCanceledAsError(): bool;

    public function setSpreadErrorToApplicationExitCode(bool $spreadErrorToApplicationExitCode = true): static;

    public function isSpreadErrorToApplicationExitCode(): bool;

    /** @param array<mixed> $env */
    public function start(callable $callback = null, array $env = []): void;

    public function stop(float $timeout = 10, int $signal = null): ?int;

    public function getStartTime(): float;
}
