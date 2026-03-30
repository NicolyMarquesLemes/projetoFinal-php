CREATE DATABASE portal;
USE portal;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255)
);

CREATE TABLE noticias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255),
    noticia TEXT,
    data DATETIME DEFAULT CURRENT_TIMESTAMP,
    autor INT,
    imagem VARCHAR(255),
    pais VARCHAR(100),
    FOREIGN KEY (autor) REFERENCES usuarios(id)
);