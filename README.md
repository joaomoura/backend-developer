# Projeto BackEnd API | Loopa 



**Elaborar uma API que receba um arquivo e faça a leitura e interpretação dos dados contidos nas linhas do arquivo.**

Projeto desenvolvido com o Micro-framework PHP Lumen
Servidor enbutido da própria instalação do PHP 7.4.9 
Postam para os testes da API

---

## Configurações

Usar o arquivo .env.example e criar o arquivo .env com base naquele

1. Definir os dados de acesso do Banco de dados para criar as tabelas de Usuários e as tabelas sales e installments caso queira ter os registros das vendas no Banco de dados (no código o trecho responsável por isso está comentado);
2. No "**.env.example**" consta a definição também da variável "**JWT_KEY**" responsável pela autenticação via **JWT**.
3. Subir as seguintes Bibliotecas e/ou Classes terceiras para o funcionmento dos recursos necessários, via Composer:
4. Executar o comando ``` Composer update ``` para atualizar as bibliotecas e classes terceiras para o correto funcionamento do projeto.

## Como Instalar

Instalação do Lumen via CLI
Instale o Lumen emitindo o comando Composer *create-project* em seu terminal:

```
composer create-project --prefer-dist laravel/lumen <nome-do-projeto>
```

## Subindo no Servidor

Para subir o projeto num servidor local, você pode usar o servidor de desenvolvimento embutido do PHP: 

```
 php -S localhost:8000 -t public
```

## Executando o Projeto 

Os serviços da API podem ser testados através das seguintes requisições:

1. Para registro de um usuário: POST "*http://localhost:8000/api/register*", parâmetros: "*email*" e "*password*", se ocorrer tudo bem o usuário é cadastrado e redirecionado para a tela de login, caso contrário "e-mail já esteja cadastrado" retorna para a tela de cadastro novamente;
2. Para Login/Autenticação: POST "*http://localhost:8000/api/login*", parâmetros: "*email*" e "*password*", se ocorrer tudo bem o usuário é redirecionado para a página inicial "Dashboard", caso contrário retorna para a tela de login;
3. Submeter arquivo para representação dos dados das vendas: POST "*http://localhost:8000/api/sales*", parâmetro: "*file*" com o caminho do arquivo text, vide exemplo: "*C:\\\\[pasta-localhost]\\\\loopa\\\\public\\\\uploads\\\\sales.txt*".

Assim espera-se que seja possível a leitura do arquivo passado como parâmetro, vide exemplo do arquivo txt e sua representação:

```
12320201012000011132703Comprador 1         06050190
32120201013000015637504Comprador 2         06330000
23120201014000026370003Comprador 3         01454000
```

Resposta:

```
{
    "sales": [
        {
            "id": "123",
            "date": "2020-10-12",
            "amount": 1113.27,
            "installments": [
                {
                    "installment": 1,
                    "amount": 371.09,
                    "date": "2020-11-11"
                },
                {
                    "installment": 2,
                    "amount": 371.09,
                    "date": "2020-12-11"
                },
                {
                    "installment": 3,
                    "amount": 371.09,
                    "date": "2021-01-11"
                }
            ],
            "customer": {
                "name": "Comprador 1",
                "address": {
                    "street": "Rua Deodate Pereira Rezende",
                    "neighborhood": "Jaguaribe",
                    "city": "Osasco",
                    "state": "SP",
                    "postal_code": "06050-190"
                }
            }
        },
        {
            "id": "321",
            "date": "2020-10-13",
            "amount": 1563.75,
            "installments": [
                {
                    "installment": 1,
                    "amount": 390.96,
                    "date": "2020-11-12"
                },
                {
                    "installment": 2,
                    "amount": 390.96,
                    "date": "2020-12-14"
                },
                {
                    "installment": 3,
                    "amount": 390.96,
                    "date": "2021-01-11"
                },
                {
                    "installment": 4,
                    "amount": 390.96,
                    "date": "2021-02-10"
                }
            ],
            "customer": {
                "name": "Comprador 2",
                "address": {
                    "street": "Estrada do Copiúva",
                    "neighborhood": "Vila da Oportunidade",
                    "city": "Carapicuíba",
                    "state": "SP",
                    "postal_code": "06330-000"
                }
            }
        },
        {
            "id": "231",
            "date": "2020-10-14",
            "amount": 2637,
            "installments": [
                {
                    "installment": 1,
                    "amount": 879,
                    "date": "2020-11-13"
                },
                {
                    "installment": 2,
                    "amount": 879,
                    "date": "2020-12-14"
                },
                {
                    "installment": 3,
                    "amount": 879,
                    "date": "2021-01-12"
                }
            ],
            "customer": {
                "name": "Comprador 3",
                "address": {
                    "street": "Avenida Cidade Jardim",
                    "neighborhood": "Jardim Paulistano",
                    "city": "São Paulo",
                    "state": "SP",
                    "postal_code": "01454-000"
                }
            }
        }
    ]
}
```

## Extras - FrontEnd

Os serviços da API podem ser testados também atrasvés do Front que consome a API desenvolvido também pelo Lumen, na mesma aplicação:
Lembrando que todas as funções são feitas via consumo da API.

Para isso eu subi a aplicação em um outro servidor:

```
 php -S localhost:8080 -t public
```

Dessa forma o front ficou consumindo do servidor anterior o "*localhost:8000*".

Seguem os acessos:

1. Para registro de um usuário pelo Front acessar "*http://localhost:8080/register*", fornecer os dados de "*email*" e "*password*", logo depois submeter o formulário para registro;
1. Para Login/Autenticação acessar *http://localhost:8080/login*", preencher os parâmetros: "*email*" e "*password*", logo depois submeter o formulário, com issoo sistema irá autenticará o usuário tornando possível o consumo da API de Vendas;
3. Submeter arquivo fia formulário no front para representação dos dados das vendas, acessar "*http://localhost:8080*", escolher o arquivo para download via tela de seleção e submeter o formulário, o arquivo deverá ser subido no servidor e o mesmo será usada para consumo da API que trará o resultado dos dados das Vendas para representção na tela da Tabela e no exemplo do formato JSON.
4. Ao consumir a API pelo Front, caso o usuário não esteja autenticado ou não autorizado o mesmo é remetido à tela de login para autenticação. Evitando que o usuário receba um erro inesperado.

Assim espera-se que seja possível a leitura do arquivo passado como parâmetro, vide exemplo do arquivo txt e sua representação:

## Extras - Banco de Dados

Também fiz com que ao acessar a API e subir um arquivo TXT os dados fossem lançados no Banco de Dados.

Deixei essa opção desabilita por padrão "*Linhas Comentadas*" no arquivo da chamada da API "*http://localhost:8000/api/sales*".

Para usar ess opção extrar basta descomentar esses trechos.

As Tabelas podem ser criadas através das Migrations via CLI

```
 php artisan migrate --force
```

