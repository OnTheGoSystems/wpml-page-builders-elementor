#!/bin/bash

STAGED_FILES_CMD=`git diff --cached --name-only --diff-filter=ACMR HEAD | grep \\\\.php`

# Determine if a file list is passed
if [[ "$#" -eq 1 ]]
then
	oIFS=$IFS
	IFS='
	'
	STAGED_FILES="$1"
	IFS=${oIFS}
fi
STAGED_FILES=${STAGED_FILES:-$STAGED_FILES_CMD}

echo "Checking PHP Lint..."
for FILE in ${STAGED_FILES}
do
	if [[ ${FILE} != tests/* ]]
	then
		FILES="$FILES ./$FILE"
	fi
done

if [[ "$FILES" != "" ]]
then
    echo "Running duplicates checks..."
    echo "vendor/bin/phpcpd --exclude tests --exclude vendor --${FILES}"
    vendor/bin/phpcpd --exclude tests --exclude vendor -- ${FILES}

    if [[ $? != 0 ]]
    then
        echo "Fix the error before commit!"
        exit 1
    else
        echo ":: Duplicates checks: OK"
    fi
fi

exit $?
