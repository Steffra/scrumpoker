#!/bin/sh

STAGED_FILES_CMD=$(git diff --cached --name-only --diff-filter=ACM | grep ".php\{0,1\}$")

# Determine if a file list is passed
if [ "$#" -eq 1 ]
then
    oIFS=$IFS
    IFS='
    '
    SFILES="$1"
    IFS=$oIFS
fi
SFILES=${SFILES:-$STAGED_FILES_CMD}

echo "Checking PHP Lint..."
for FILE in $SFILES
do
    php -l -d display_errors=0 $FILE
    if [ $? != 0 ]
    then
        echo "Fix the error before commit."
        exit 1
    fi
    FILES="$FILES $FILE"
done

if [ "$FILES" != "" ]
then
    echo "Running lint fixer"
    backend/vendor/bin/phpcbf -d --extensions=php,inc --standard=pipelines/config/PSR12_udemx.xml $FILES
    git add $FILES

    backend/vendor/bin/phpcs -d --extensions=php,inc --standard=pipelines/config/PSR12_udemx.xml $FILES
    if [ $? != 0 ]
    then
        echo "Errors found not fixable automatically"
        exit 1
    fi


fi

exit $?