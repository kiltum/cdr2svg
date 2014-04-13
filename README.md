cdr2svg
=======

Convert .cdr and .plt files to .svg

Problem: nothing exist in linux world, that able to convert cdr to .svg without errors.

Option 1
--------
/usr/bin/libreoffice --headless --convert-to svg file.cdr

Usually not works (but with GUI all fine).

Option 2
--------

/usr/bin/libreoffice --headless --convert-to eps file.cdr

/usr/bin/gs -sDEVICE=svg -dBATCH -dNOPAUSE -sOutputFile=file.svg file.eps

Works good, but result .svg is B&W and coorinates in file looks for me very strange

Option 3
--------
Install xvfb, libreoffice, and some GUI-testing tool. Make script like "open file, export to svg, close file".

So here is step-by step instruction. I use OpenVZ, so you warned ;)


vzctl create 1015 --ostemplate ubuntu-12.04-x86

vzctl set 1015 --hostname HOSTNAME --ipadd 10.100.0.15 --ram 1G --diskspace 20G --swappages=100:100 —save

vzctl set 1015 --privvmpages=256000 --tcprcvbuf=27033600 —save

vzctl start 1015

vzctl enter 1015

apt-get update

apt-get install python-software-properties

add-apt-repository ppa:xpresser-team/ppa

add-apt-repository ppa:libreoffice

apt-get update

apt-get upgrade

apt-get install python-xpresser libreoffice xvfb x11vnc xterm apache2 php5 php5-json mysql-server mysql-client php5-cli subversion php5-mysql

apt-get install mc

mcedit /etc/apache2/sites-enabled/000-default

DocumentRoot /var/www/html

a2enmod suexec

/etc/init.d/apache2 restart

chmod 777 /root

mcedit /etc/rc.local

ADD this

Xvfb :0 -ac -screen :0 1024x768x16 & > /root/xlog

x11vnc -display :0  -passwd 123 --many & > /root/vnc

DISPLAY=:0 libreoffice &

/var/www/convert/conv &


cp /var/www/testfile.cdr /root/1.cdr

ssh -L 5901:10.100.0.15:5900 root@server_with_VPS

GUI ! Open in libreoffice /root/1.cdr and close it (but not LO!)

apt-get install python-dbus

apt-get install --reinstall gir1.2-gtk-2.0 python-software-properties software-properties-gtk


DISPLAY=:0 python ./test.py

this must work now ^^^^

cd /var/www/convert/chmod +x conv

How it works
------------

conv just looking for /tmp/1.cdr, after it appears, it copy to /root
and ask xpresser to open file, export them to 1.svg and close.

after that i call normalize.php, that fix result svg (row and height set properley)
and remove any text

WARNING: normalize.php REMOVE text in .svg. I need it ;)

thats all ;) yep it very buggy and stupid, but allow me to convert
MANY .cdr files to .svg

Have fun!