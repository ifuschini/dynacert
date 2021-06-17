if [ "$( docker container inspect -f '{{.State.Running}}' db )" == "true" ]; 
    then
    ip=$(docker inspect -f "{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}" db); #> ./conf/db_ip.txt;
    echo $ip |tr -d '\n' > ./conf/db_ip.txt;
    docker run --add-host dbhost:$ip -ti webserver /bin/bash
    else
    echo "docker container 'db' is not running"
fi