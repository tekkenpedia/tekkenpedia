# tekkenpedia

https://tekkenpedia.github.io/tekkenpedia/

```bash
bin/start

bin/console concat:videos
bin/console convert:video:gif
bin/console videos:to:gif

bin/console generate:html
```

# Add move

Le token github doit avoir accès à `Actions` (read and write) et `Contents` (read).

https://github.com/settings/personal-access-tokens/new

# Générer une grille Bootstrapa

```bash
docker run --rm -u $(id -u):$(id -g) -v $(pwd):/app -w /app node:20 sh -c "npm init -y && npm install bootstrap sass && echo '\$grid-columns: 13; @import \"node_modules/bootstrap/scss/bootstrap\";' > custom.scss && npx sass custom.scss bootstrap-13.css"
```
