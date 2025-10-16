---
title: "Summary"
---

# PHP parallel processes

`php-parallel-processes` is an open source tool to execute processes in parallel, with these key features:

- Install it as a [Composer dependency](installation/composer-dependency.md) or use the [Docker images](installation/docker-image.md) to avoid installing anything
- Configure your processes in PHP
- Verbosity to define if you want to execute processes without showing each process output, show execution time, show only error outputs or all outputs
- Configure when your process should start: immediately, wait for another process (successful, failed or terminated), bootstrap or tear down
- Configure each process: name, command, max execution time, output verbosity, etc.
- Choose your theme, or create your own

## Changer la couleur des liens (variable --md-typeset-a-color)

Vous pouvez modifier la variable CSS --md-typeset-a-color utilisée par le thème Markdown de deux façons :

- Option globale — dans votre CSS VitePress (par exemple .vitepress/theme/index.css) :
```css
:root {
  --md-typeset-a-color: #1e88e5; /* nouvelle couleur des liens */
}
```

- Option locale — directement dans une page Markdown (affecte seulement cette page) :
```html
<style>
:root {
  --md-typeset-a-color: #1e88e5;
}
</style>
```

Après modification, rechargez la documentation. Si vous avez besoin d'ajuster aussi l'état :hover ou le poids, ajoutez des règles CSS ciblant a, a:hover, etc.
