create table cadastro (
	id int not null AUTO_INCREMENT,
	nome varchar (30) not null unique,
    login varchar (20) not null unique,
    senha varchar (10) not null,
    root boolean default false,
    primary key (id)
) default charset = utf8;