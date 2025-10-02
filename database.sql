CREATE DATABASE loja_virtual;
USE loja_virtual;

CREATE TABLE produtos (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          nome VARCHAR(100) NOT NULL,
                          valor DECIMAL(10,2) NOT NULL,
                          estoque INT NOT NULL
);

CREATE TABLE vendas (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        produto_id INT NOT NULL,
                        quantidade INT NOT NULL,
                        valor_total DECIMAL(10,2) NOT NULL,
                        data_venda DATETIME DEFAULT CURRENT_TIMESTAMP,
                        FOREIGN KEY (produto_id) REFERENCES produtos(id)
);
