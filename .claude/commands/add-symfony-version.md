---
description: Add a new Symfony version to CI (DependenciesVersions, Dockerfile, phpstan configs)
argument-hint: <symfony-version>
allowed-tools: [Read, Edit, Write, Glob, Grep, Bash]
---

Add Symfony version **$ARGUMENTS** to the CI pipeline. Follow each step exactly.

## Step 1: Validate input

The argument must be a Symfony version like `7.4`. If `$ARGUMENTS` is empty or does not match the pattern `X.Y` (single digit dot single digit), stop and tell the user the correct usage: `/add-symfony-version 7.4`.

Derive these values from the version `X.Y`:
- **MAJOR** = X (e.g. `7`)
- **MINOR** = Y (e.g. `4`)
- **ENV suffix** = X_Y (e.g. `7_4`)
- **dir suffix** = X-Y (e.g. `7-4`)

## Step 2: Update `composer.json`

Read the file. Update the `require` section so that `symfony/console` and `symfony/process` version constraints include the new version X.Y. If the new version is already within the existing constraint range, no change is needed. If adding a new major version (e.g. Y is `0`), add `|| ^X.0` to the constraint — this covers all future minors of that major (e.g. `^8.0` covers 8.0, 8.1, 8.2, etc.). If adding a minor within an existing major, extend the upper bound to cover `X.Y.*` (e.g. change `<8.0.0` to `<X.(Y+1).0`).

## Step 3: Update `bin/DependenciesVersions.php`

Read the file. In the `$this->symfonyVersions` chain, add `->add('X.Y')` immediately before `->setReadOnly()`. Follow the existing indentation (16 spaces).

## Step 4: Update `docker/ci/Dockerfile`

Read the file. Three sections need updating:

### 4a: Add environment variable

Find the last `ENV COMPOSER_HOME_SYMFONY_` line. Add immediately after it:

```
ENV COMPOSER_HOME_SYMFONY_X_Y=/composer/symfony-X-Y
```

### 4b: Add composer global require

Find the `# Install Symfony components` section. Add a new line after the last `composer global require` line, following the existing pattern:

```
    && COMPOSER_HOME=${COMPOSER_HOME_SYMFONY_X_Y} composer global require symfony/process:X.Y.* symfony/console:X.Y.* \
```

Make sure the backslash continuation is preserved on the previous line.

### 4c: Add cleanup block

Find the last `rm -rf` block for a Symfony version (the one with `COMPOSER_HOME_SYMFONY_`). Add a new block immediately after it, before `&& rm -rf /tmp/*`:

```
    && rm -rf \
        ${COMPOSER_HOME_SYMFONY_X_Y}/.htaccess \
        ${COMPOSER_HOME_SYMFONY_X_Y}/cache \
        ${COMPOSER_HOME_SYMFONY_X_Y}/composer.json \
        ${COMPOSER_HOME_SYMFONY_X_Y}/composer.lock \
```

## Step 5: Create phpstan config files

1. Glob `config/ci/phpstan/php-*.neon` to find all existing PHP versions in use.
2. Extract the unique PHP versions (e.g. `8.2`, `8.3`, `8.4`, `8.5`).
3. For each PHP version, derive **phpVersion int** = X0Y00 (e.g. `80200` for `8.2`) — pad minor to two digits, append two zeros.
4. For each PHP version `P.Q`, create `config/ci/phpstan/php-P.Q.symfony-X.Y.neon` with this exact content (no trailing newline after the last line):

```
includes:
    - common.neon
parameters:
    phpVersion: <phpVersion int>
    tmpDir: ../../../var/ci/phpstan/php-P-Q/symfony-X-Y
```

Where dashes replace dots in directory names (e.g. `php-8-2/symfony-7-4`).

## Step 6: Update `changelog.md`

Read the file. Under the `### master` heading, add a line:

```
- Add compatibility for Symfony X.Y
```

If similar lines already exist, add it after the last one.

## Step 7: Build Docker image

Run `bin/ci/docker` to rebuild the CI Docker image with the new Symfony version. This may take several minutes.

## Step 8: Run CI validation

Run `bin/ci/validate` to verify everything passes. If it fails, fix the errors and re-run until it passes.

## Step 9: Report

List all files that were modified or created.

At the very end, display this reminder message in bold yellow (using markdown):

> **⚠️ Don't forget to push the Docker image: `bin/ci/docker --push`**
