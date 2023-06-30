echo $RANDOM | { read val; sed "3s/.*/\/\/new\ cache\ content\ $val/g" spa\ ces.php > code.php; }
git add .
git commit -m "updated code.php"
echo $RANDOM | { read val; sed "3s/.*/\/\/new\ cache\ content\ $val/g" spa\ ces.php > code-$val.php; }
git add .
git commit -m "New codeXXX.php"
echo $RANDOM | { read val; sed "1s/.*/\/\/new\ cache\ content\ $val/g" test.js > code-$val.js; }
git add .
git commit -m "updated code.js"