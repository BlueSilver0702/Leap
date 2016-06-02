#!/bin/sh
 
git commit -avm "snapshot at ${date}"
git push
git pull