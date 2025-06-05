#!/bin/bash
cd /var/www/certificados
git add .
git commit -m "Auto push $(date '+%Y-%m-%d %H:%M:%S')"
git push
