#!/bin/bash

# Verifica se o MySQL está rodando
if ! systemctl is-active --quiet mysql; then
    echo "Iniciando o MySQL..."
    sudo service mysql start
fi

# Executa o script SQL
echo "Criando banco de dados e tabelas..."
mysql -u root -p"$1" < database/setup.sql

echo "Banco de dados configurado com sucesso!"