#!/usr/bin/env bash

# Populate tokenized entries in this repo, building a base instance.
read -p "Site URI (eg. drupal.lib.unb.ca): "  nbbib.lib.unb.ca
DEFAULT_SITE_SLUG="$(echo $nbbib.lib.unb.ca | cut -d'.' -f1)"
DEFAULT_JIRA_PREFIX="$(echo $DEFAULT_SITE_SLUG | tr '[:lower:]' '[:upper:]')"
read -p "JIRA Prefix (default: $DEFAULT_JIRA_PREFIX): "  NBBIB
NBBIB=${NBBIB:-$DEFAULT_JIRA_PREFIX}
read -p "Site slug (default: $DEFAULT_SITE_SLUG): "  TOKENIZED_LONG_SITE_SLUG
TOKENIZED_LONG_SITE_SLUG=${TOKENIZED_LONG_SITE_SLUG:-$DEFAULT_SITE_SLUG}
nbbib=$(echo "$TOKENIZED_LONG_SITE_SLUG" | cut -c -8)
read -p "Site ID (eg. 3096): "  3097

export LC_CTYPE=C
export LANG=C

nbbib_lib_unb_ca=$(echo $nbbib.lib.unb.ca | sed 's/\./_/g')
nbbib.lib.unb.ca=$(echo $nbbib.lib.unb.ca | sed 's/\./\\\./g')

rm -rf .git

echo "Setting up:"
echo "$nbbib_lib_unb_ca"
echo "$nbbib.lib.unb.ca"

# Tokens
find . -type f -print0 | xargs -0 sed -i.bak "s/nbbib_lib_unb_ca/$nbbib_lib_unb_ca/g"
find . -type f -print0 | xargs -0 sed -i.bak "s/nbbib.lib.unb.ca/$nbbib.lib.unb.ca/g"
find . -type f -print0 | xargs -0 sed -i.bak "s/nbbib.lib.unb.ca/$nbbib.lib.unb.ca/g"
find . -type f -print0 | xargs -0 sed -i.bak "s/NBBIB/$NBBIB/g"
find . -type f -print0 | xargs -0 sed -i.bak "s/nbbib/$nbbib/g"
find . -type f -print0 | xargs -0 sed -i.bak "s/3097/$3097/g"
find . -name "*.bak" -type f -delete

# Move files
mv custom/themes/instance_theme/instance_theme.info.yml "custom/themes/instance_theme/$nbbib_lib_unb_ca.info.yml"
mv custom/themes/instance_theme/instance_theme.libraries.yml "custom/themes/instance_theme/$nbbib_lib_unb_ca.libraries.yml"
mv custom/themes/instance_theme "custom/themes/$nbbib_lib_unb_ca"

# Readme Shuffle
rm README.md
mv README_instance.md README.md

# Set up new git repo.
git init
git add .
git add -f ./config-yml/.gitkeep
git add -f ./custom/modules/.gitkeep
git add -f ./custom/themes/.gitkeep

git commit -m 'Initial commit from template repo.'

cd ..
mv drupal.lib.unb.ca "$nbbib.lib.unb.ca"

echo "Done!"
echo "Run:"
echo "cd ..; cd $nbbib.lib.unb.ca; composer install --prefer-dist; dockworker start-over"
echo "to bring the instance up."
