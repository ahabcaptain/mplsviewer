Project: mpls viewer
Author: Aki Hermann Barkarson, CCIE # 27552

This is my first time doing anything like this.. so code needs a bit of clean-up. But it works..

Installation of 0.4b:

1) Create a database in mysql and grant some user access.
2) Import mplsviewer_0.4b.sql ex "mysql -p database < mplsviewer_0.4b.sql"
3) Edit "includes/config.php" for correct username/password.
3) Make sure the text files check.txt and vrfenabled.txt are writeable by webserver. ex "chown apache:apache vrfenabled.txt" or chmod it..
4) Manually edit parse-vrf.php and change the directories where rancid is located. the provided file points to two different locations, delete or add as you wish.
5) After imported you can run show-one-vrf.php or show-one-device.php .. they link to eachother.

hopefully you'll make it work.. :-)

the 0.5a version is mostly getting v6 and v4 address family sorted.. vrf definitions are parsed in 0.4b but viewers need updating.
