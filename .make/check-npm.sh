#!/bin/bash

STAGED_FILES=`git diff --cached --name-only --diff-filter=ACMR HEAD | grep -E '^src/(yarn.lock|package.json)'`

for FILE in ${STAGED_FILES}
do
	FILES="$FILES ./$FILE"
done

if [[ "$FILES" != "" ]]
then
	echo "Validating package.json"
    npm doctor

    if [[ $? != 0 ]]
    then
    	echo "Tip: if you get a failed 'npm config get registry' check, try \`npm config set registry https://registry.npmjs.org/\`"
    	echo "Tip: if you get a failed 'Perms check on local node_modules' check, try \`sudo chown -R $(whoami) ./node_modules\`"
    	echo "Tip: if you still get a failed 'Perms check on local node_modules' check, try \`rm -rf node_modules\` followed by \`npm install\`"
        exit 1
    fi
fi

exit $?
