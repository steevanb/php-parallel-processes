---
hide:
  - toc
---

# Work on the code

You can clone the project:
```bash
git clone git@github.com:steevanb/php-parallel-processes.git
```

Then, when you to execute your local code, you need to add a volume on `/composer/vendor/steevanb/php-parallel-processes`:
```bash hl_lines="6"
docker \
    run \
        --rm \
        -it \
        -v "$(pwd)":/app \
        -v "$(pwd)":/composer/vendor/steevanb/php-parallel-processes \
        steevanb/php-parallel-processes:{{ package_version }}-alpine \
            php /app/parallel-processes.php
```

# Work on the documentation

We use [readthedocs](https://about.readthedocs.com/), 
[mkdocs](https://docs.readthedocs.com/platform/stable/intro/mkdocs.html) 
and [Material for MkDocs](https://squidfunk.github.io/mkdocs-material/reference).

Documentation is written in Markdown in [docs/](https://github.com/steevanb/php-parallel-processes/tree/readthedocs/docs).

See [Material for MkDocs](https://squidfunk.github.io/mkdocs-material/reference/code-blocks/) for Markdown syntaxes and examples.
