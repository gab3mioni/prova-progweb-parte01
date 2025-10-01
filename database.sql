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

INSERT INTO produtos (nome, valor, estoque) VALUES
                                                ('Notebook Lenovo', 3500.00, 10),
                                                ('Smartphone Samsung', 2200.00, 15),
                                                ('Fone de Ouvido Bluetooth', 250.00, 30),
                                                ('Monitor LG 24"', 900.00, 8),
                                                ('Teclado Mecânico', 350.00, 20);
