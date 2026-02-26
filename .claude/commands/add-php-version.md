---
description: Add a new PHP version to CI (DependenciesVersions, Dockerfile, phpstan configs)
argument-hint: <php-version>
allowed-tools: [Read, Edit, Write, Glob, Grep, Bash]
---

Add PHP version **$ARGUMENTS** to the CI pipeline. Follow each step exactly.

## Step 1: Validate input

The argument must be a PHP version like `8.5`. If `$ARGUMENTS` is empty or does not match the pattern `X.Y` (single digit dot single digit), stop and tell the user the correct usage: `/add-php-version 8.5`.

Derive these values from the version `X.Y`:
- **MAJOR** = X (e.g. `8`)
- **MINOR** = Y (e.g. `5`)
- **phpVersion int** = X0Y00 (e.g. `80500`) — pad minor to two digits, append two zeros

## Step 2: Update `bin/DependenciesVersions.php`

Read the file. In the `$this->phpVersions` chain (around line 33-38), add `->add('X.Y')` immediately before `->setReadOnly()`. Follow the existing indentation (16 spaces).

## Step 3: Update `docker/ci/Dockerfile`

Read the file. Find the last `# PHP X.Y` comment block in the `apt-get install` section (the block with `phpX.Y-cli`, `phpX.Y-dom`, `phpX.Y-mbstring`). Add a new block immediately after it with the same pattern:

```
        # PHP X.Y
        phpX.Y-cli \
        phpX.Y-dom \
        phpX.Y-mbstring \
```

Make sure the backslash continuation is preserved on the line before the new block.

## Step 4: Create phpstan config files

1. Glob `config/ci/phpstan/php-*.neon` to find all existing Symfony versions in use.
2. Extract the unique Symfony versions (e.g. `7.0`, `7.1`, `7.2`, `7.3`).
3. For each Symfony version `Z.A`, create `config/ci/phpstan/php-X.Y.symfony-Z.A.neon` with this exact content (no trailing newline after the last line):

```
includes:
    - common.neon
parameters:
    phpVersion: <phpVersion int>
    tmpDir: ../../../var/ci/phpstan/php-X-Y/symfony-Z-A
```

Where dashes replace dots in directory names (e.g. `php-8-5/symfony-7-0`).

## Step 5: Update `changelog.md`

Read the file. Under the `### master` heading, add a line:

```
- Add compatibility for PHP X.Y
```

If similar lines already exist, add it after the last one.

## Step 6: Build Docker image

Run `bin/ci/docker` to rebuild the CI Docker image with the new PHP version. This may take several minutes.

## Step 7: Run CI validation

Run `bin/ci/validate` to verify everything passes. If it fails, fix the errors and re-run until it passes.

## Step 8: Report

List all files that were modified or created.

At the very end, display this reminder message in bold yellow (using markdown):

> **⚠️ Don't forget to push the Docker image: `bin/ci/docker --push`**
