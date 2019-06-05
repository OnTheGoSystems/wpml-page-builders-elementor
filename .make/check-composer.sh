#!/bin/bash

STAGED_FILES=`git diff --cached --name-only --diff-filter=ACMR HEAD | grep -E '^composer.(lock|json)'`

for FILE in ${STAGED_FILES}
do
	FILES="$FILES ./$FILE"
done

if [[ "$FILES" != "" ]]
then
	echo "Validating composer.json"
    composer validate --no-check-all

    if [[ $? != 0 ]]
    then
        exit 1
    fi
fi

exit $?
