create table pessoas (
	login varchar (20) not null,
	nomePessoa varchar (30) not null unique,
    senha varchar (10) not null,
    vinculo enum ('Estudante', 'Servidor', 'Segurança') default 'Estudante',
    telefone varchar (15) not null,
    primary key (login)
) default charset = utf8;