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
	php -l -d display_errors=0 ./${FILE}
	if [[ $? != 0 ]]
	then
		echo "Fix the error before commit."
		exit 1
	else
		echo ":: PHP Lint: OK"
	fi

	if [[ ${FILE} != tests/* ]]
	then
		FILES="$FILES ./$FILE"
	fi

	FILES="$FILES ./$FILE"
done

if [[ "$FILES" != "" ]]
then
    echo "Running compatibility checks..."
    ./vendor/bin/phpcs --standard=./phpcs.compatibility.xml --colors ${FILES}

    if [[ $? != 0 ]]
    then
        echo "Fix the error before commit!"
        exit 1
    else
        echo ":: Compatibility checks: OK"
    fi

    echo "Running Code Sniffer..."
    ./vendor/bin/phpcs --colors ${FILES}

    if [[ $? != 0 ]]
    then
        echo "Fix the error before commit!"
        echo "Run"
        echo "  ./vendor/bin/phpcbf $FILES"
        echo "for automatic fix or fix it manually."
        exit 1
    else
        echo ":: Code Sniffer: OK!"
    fi
fi

exit $?
