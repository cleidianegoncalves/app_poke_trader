
# Aplicação para simular trocas de pokemons da primeira geração

### Stacks
* [PHP](https://www.php.net/)
* [Symfony](https://symfony.com/releases/4.1)
* [MariaDB](https://mariadb.org/)
* [Doctrine](https://www.doctrine-project.org/)
* [Docker](https://www.docker.com/)

### Instalação com ambiente Docker

Clone o repositório:
```
$ git clone https://github.com/cleidianegoncalves/app_poke_trader.git
```

Copie o `docker-compose-model.yml` o para a pasta pai do projeto e renomeie para `docker-compose.yml`.

Acesse a pasta pai do projeto e no terminal execute o comando:
```
docker-compose up -d
```

Após criado e iniciado o conteiner, entre no container do Symfony:
```
docker exec -it symfony_poke_trader bash
```

Dentro do container entre na pasta app_poke_trader e instale as dependências do projeto com o comando:
```
composer install
```

Após a instalação é necessário executar a migration do banco com o comando:
```
php bin/console doctrine:migrations:migrate
```

Após isso acesse a aplicação pelo navegador no endereço
http://localhost:8000/

### Demonstração com Heroku

A aplicação está disponível para visualização no Heroku através do endereço
https://app-poke-trader.herokuapp.com/

### Utilizando a Aplicação

O objetivo da aplicação é simular trocas de pokemons e dizer se elas são justas ou não. Para a troca ser considerada justa, 
a diferença de XP entre os pokemons oferecidos e preteridos deve ser de no máximo 30 pontos.
Na tela principal é possível selecionar até 6 Pokemons de cada lado, ao selecionar o XP de cada jogador é exibido logo 
abaixo dos Pokemons escolhidos. Aí é só clicar no botão de verificar que o sistema informa se a troca é ou não justa. 
Você pode salvar a silumação de troca e visualizá-la no histórico com base na data e hora da simulação.
