create table usuarios (
	login varchar (20) not null primary key,
    senha varchar (15) not null,
    nomePessoa varchar (30) not null unique
) default charset = utf8;