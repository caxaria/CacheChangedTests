mkdir php ; mkdir js; 
echo $RANDOM | { read val; sed "3s/.*/\/\/new\ cache\ content\ $val/g" spa\ ces.php > ./php/code-$val.php; git add . ; git commit -m "auto: new code-$val.php" ; sleep 1 ; }
echo $RANDOM | { read val; sed "3s/.*/\/\/new\ cache\ content\ $val/g" spa\ ces.php > ./php/code-$val.php; git add . ; git commit -m "auto: new code-$val.php" ; sleep 1 ; }
echo $RANDOM | { read val; sed "1s/.*/\/\/new\ cache\ content\ $val/g" test.js > ./js/code-$val.js; git add . ; git commit -m "auto: updated code-$val.js" ; sleep 1 ; }

echo "done"