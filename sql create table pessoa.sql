create table pessoas (
	cpf varchar (14) not null,
	nome varchar (30) not null unique,
    funcao enum ('Estudante', 'Servidor', 'Segurança') default 'Estudante', 
    email varchar (255) not null,
    telefone varchar (15) not null,
    primary key (cpf)
) default charset = utf8;