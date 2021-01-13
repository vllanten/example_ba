mysql -uroot -p[pass_db] -e "Create database gestion;"
mysql -uroot -p[pass_db] -e "CREATE TABLE usuario ( id INT(5) auto_increment NOT NULL, nombres varchar(250) NOT NULL, apellidos varchar(250) NOT NULL, correo varchar(250) NOT NULL, password varchar(100) NOT NULL, primary key(id));"
mysql -uroot -p[pass_db] -e "insert into usuario (nombres, apellidos, correo, password) values ('usuario1', 'usuario1', 'usuario1@example.com', '123456'),('usuario2', 'usuario2', 'usuario2@example.com', '123456');"
mysql -uroot -p[pass_db] -e "CREATE TABLE token (  id INT(5) auto_increment NOT NULL, id_user INT(5) NOT NULL, token varchar(250) NOT NULL, fcreacion datetime NOT NULL, fuso datetime NOT NULL , primary key(id));"
mysql -uroot -p[pass_db] -e "ALTER TABLE gestion.token ADD CONSTRAINT token_FK FOREIGN KEY (id_user) REFERENCES gestion.usuario(id) ON DELETE CASCADE ON UPDATE CASCADE;"


mysql -uroot -p[pass_db] -e "GRANT ALL PRIVILEGES ON . TO 'root'@'%' IDENTIFIED BY '123456';"
mysql -uroot -p[pass_db] -e "FLUSH privileges;"