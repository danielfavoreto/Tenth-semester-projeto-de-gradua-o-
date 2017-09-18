create table alertas (
	id int not null AUTO_INCREMENT,	
	nome varchar (30) not null,
    telefone varchar (15) not null,
    status tinyint(1) default 0,
    dataHora datetime NOT NULL,
    lat DECIMAL(10, 8) NOT NULL,
    lng DECIMAL(11, 8) NOT NULL,
    login varchar(20) NOT NULL,
    primary key (id)
) default charset = utf8;