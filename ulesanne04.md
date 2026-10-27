PS C:\laragon\www\raven-kapakodu> mysql -u root -p
Enter password:
Welcome to the MySQL monitor. Commands end with ; or \g.
Your MySQL connection id is 13
Server version: 8.4.3 MySQL Community Server - GPL

Copyright (c) 2000, 2024, Oracle and/or its affiliates.

Oracle is a registered trademark of Oracle Corporation and/or its
affiliates. Other names may be trademarks of their respective
owners.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

mysql> USE kapakodu;
Database changed
mysql> SELECT COUNT(\*) AS loomade_arv FROM loomad;
+-------------+
| loomade_arv |
+-------------+
| 21 |
+-------------+
1 row in set (0.01 sec)

mysql> SELECT COUNT(\*) AS koerte_arv FROM loomad WHERE liik = 'Koer';
+------------+
| koerte_arv |
+------------+
| 10 |
+------------+
1 row in set (0.00 sec)

mysql> SELECT SUM(vanus) AS vanuste_summa FROM loomad;
+---------------+
| vanuste_summa |
+---------------+
| 83 |
+---------------+
1 row in set (0.00 sec)

mysql> SELECT AVG(vanus) AS keskmine_vanus FROM loomad;
+----------------+
| keskmine_vanus |
+----------------+
| 3.9524 |
+----------------+
1 row in set (0.00 sec)

mysql> SELECT liik, COUNT(\*) AS arv FROM loomad GROUP BY liik;
+--------+-----+
| liik | arv |
+--------+-----+
| Kass | 10 |
| Koer | 10 |
| Küülik | 1 |
+--------+-----+
3 rows in set (0.00 sec)

mysql> SELECT liik, AVG(vanus) AS keskmine_vanus FROM loomad GROUP BY liik;
+--------+----------------+
| liik | keskmine_vanus |
+--------+----------------+
| Kass | 3.5000 |
| Koer | 4.7000 |
| Küülik | 1.0000 |
+--------+----------------+
3 rows in set (0.00 sec)

mysql> SELECT staatus, COUNT(\*) AS arv FROM loomad GROUP BY staatus;
+-------------+-----+
| staatus | arv |
+-------------+-----+
| saadaval | 12 |
| broneeritud | 5 |
| hoolduses | 4 |
+-------------+-----+
3 rows in set (0.00 sec)

mysql> SELECT staatus, COUNT(_) AS arv FROM loomad GROUP BY staatus HAVING COUNT(_) >= 3;
+-------------+-----+
| staatus | arv |
+-------------+-----+
| saadaval | 12 |
| broneeritud | 5 |
| hoolduses | 4 |
+-------------+-----+
3 rows in set (0.00 sec)

mysql>
