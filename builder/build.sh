#npm run build
npm run development
rm -fr ../../dynamiccert/dynacert-server/html/builder
cp -r dist ../../dynamiccert/dynacert-server/html/builder
