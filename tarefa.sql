CREATE TABLE tb_tarefa (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    descricao VARCHAR(80),
    dataLimite DATE,
    dataLembrete DATE,
    dataRealizacao DATE,
    status CHAR(1),
    username VARCHAR(15),
    idCategoria INTEGER,
    FOREIGN KEY(username) REFERENCES tb_usuario(username),
    FOREIGN KEY(idCategoria) REFERENCES tb_categoria(id)
);
