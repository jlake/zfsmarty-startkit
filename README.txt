Name： Zend Framework & Smarty Project Starter
Author: Jlake Ou

These folders need write permission：
./data/cache
./data/contents
./data/logs
./data/sessions
./data/uploads


./smarty/cache
./smarty/compiled

-------------------------------------------------
Set write permission (Command Example)

$ chmod a+rw data/* smarty/cache smarty/compiled

-------------------------------------------------
Make symbol link to Zend (Command Example)
$ cd /home/myproject/library
$ ln -s /home/ZendFramework-1.10.8/library/Zend
