# tekkenpedia

https://tekkenpedia.github.io/tekkenpedia/

```bash
bin/start

bin/console concat:videos
bin/console convert:video:gif
bin/console videos:to:gif

bin/console generate:html
```

ffmpeg -i 720p.mp4 -c:v libx265 -an -b:v 1000k 720p-encoded-1000k.mp4
