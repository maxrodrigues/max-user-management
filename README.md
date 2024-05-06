
# User Hub

O projeto será um gerenciador de usuários com as seguintes utilizações:
- Cadastro
- Login
- Atualização de perfil
- Recuperação de senha
  Todos esses recursos serão colocados através de API.
## Feedback

Se você tiver algum feedback, por favor nos deixe saber por meio de maxuel.rodrigues@gmail.com ou abra uma PR.


## Instalação

Instale as dependências
```bash
  $ docker run --rm --interactive --tty -v $(pwd):/app composer install
```

Copie o arquivo de configuração
```bash
  $cp .env.example .env
```

Para rodar esse projeto, você vai precisar adicionar as seguintes variáveis de ambiente no seu .env
```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=user_hub
DB_USERNAME=sail
DB_PASSWORD=password
```

Inicie o servidor

```bash
  $ ./vendor/bin/sail up -d
```

Gere uma nova chave
```bash
  $ ./vendor/bin/sail artisan key:generate
```
