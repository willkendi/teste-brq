Desafio BRQ Digital
Este projeto é uma aplicação Laravel rodando com Docker, PostgreSQL, Nginx e PHP-FPM.

Pré-requisitos
Docker

Git

Postman (Para testes das requisições)

Como rodar o projeto
1. Clone o repositório
git clone https://github.com/willkendi/teste-brq.git
cd teste-brq

2. Suba os containers
docker-compose up -d --build

OBS: Na raiz do projeto possui um script docker-entrypoint.sh para iniciar o banco e criar as Migrations.

3. Teste dos métodos:
POST:

URL: http://localhost:8080/api/transactions/

Json de Exemplo:

{
  "inscricao": "11112345678901",
  "tipo_inscricao": "cnpj",
  "valor": 250.75,
  "localizacao": "São Paulo - SP"
}

GET (Listagem):

URL: http://localhost:8080/api/transactions/

GET (Por ID):

URL: http://localhost:8080/api/transactions/128fed5c-fc76-47e5-a6e2-eb9b3e4d5cca

Fluxo do projeto
Método: index() — Listar todas as transações
Cliente faz GET /api/transactions com Bearer Token
    │
Middleware CheckBearerToken valida token
    │
TransactionController@index()
    │
TransactionService->filter()
    │
TransactionRepository->getAll() (consulta banco de dados)
    │
Banco retorna lista (Collection) de Transaction entities
    │
TransactionService retorna lista para Controller
    │
Controller chama TransactionResource::collection($transactions)
    │
TransactionResource formata JSON com array de transações
    │
HTTP 200 com JSON retorna para cliente

Método: show($id) — Mostrar uma transação específica
Cliente faz GET /api/transactions/{id} com Bearer Token
    │
Middleware valida token
    │
TransactionController@show($id)
    │
TransactionService->getById($id)
    │
TransactionRepository->find($id)
    │
Banco retorna uma Transaction entity ou null
    │
TransactionService retorna entidade para Controller
    │
Controller chama new TransactionResource($transaction)
    │
TransactionResource formata JSON da única transação
    │
HTTP 200 com JSON retorna para cliente
    │
(se não encontrado, retorna HTTP 404)

Método: store() — Criar uma nova transação
Cliente faz POST /api/transactions com Bearer Token + dados no body
    │
Middleware valida token
    │
TransactionController@store(Request $request)
    │
Valida dados via FormRequest ou manualmente
    │
TransactionService->create($dados)
    │
TransactionRepository->create($dados)
    │
Banco cria registro e retorna Transaction entity criada
    │
TransactionService retorna entidade criada para Controller
    │
Controller chama new TransactionResource($transaction)
    │
TransactionResource formata JSON da transação criada
    │
HTTP 201 Created com JSON retorna para cliente

DECISÕES TÉCNICAS
Aqui estão as principais decisões técnicas que guiaram o desenvolvimento deste projeto:

Docker: A escolha do Docker foi fundamental, pois era um dos requisitos mínimos do projeto e oferece uma grande vantagem ao facilitar a execução da aplicação em diferentes ambientes e máquinas, garantindo consistência e agilidade no desenvolvimento e implantação.

Linguagem de Programação: A escolha do PHP foi motivada pela familiaridade e experiência, garantindo a eficiência e a conformidade com os requisitos do projeto.

Framework: Optou-se pelo Laravel devido à sua praticidade no desenvolvimento de APIs e à sua capacidade de promover a padronização e a organização do código.

Arquitetura: A aplicação segue os princípios do Domain-Driven Design (DDD), com uma clara separação entre as camadas de Service e Repository, o que contribui para a modularidade e a manutenibilidade do código.

Banco de Dados: O PostgreSQL foi selecionado como o sistema de gerenciamento de banco de dados relacional. Acredita-se que este seja um banco de dados robusto e amplamente utilizado, adequado para lidar com um grande volume de usuários e transações, características importantes para o contexto do cliente. Para facilitar a inicialização do ambiente, um script docker-entrypoint.sh foi incluído na raiz do projeto, responsável por subir o banco de dados e aplicar as migrações necessárias.

Estratégia de Testes: Foram realizados testes de integração da API, com foco nos métodos essenciais (index, show, store), para garantir o correto funcionamento das funcionalidades implementadas.

Identificadores Únicos (UUID): A utilização de UUIDs (Universally Unique Identifiers) para as transações visa aumentar a segurança da aplicação, fornecendo identificadores únicos e difíceis de prever.

Retorno do UUID na Criação: Para facilitar a avaliação e o teste do método getById, o retorno do UUID da transação recém-criada no método store foi implementado, simplificando a recuperação imediata da transação.

PONTOS DE MELHORIA
Centralização de Validações: As validações de dados poderiam ser removidas da camada de Controller e movidas para camadas mais apropriadas, como Form Requests dedicados ou Service Layer, promovendo uma maior separação de responsabilidades e reutilização de código.

Tratamento de Erros Aprimorado: A implementação de uma tratativa de erros mais robusta e padronizada é essencial. Isso incluiria a criação de classes de exceção personalizadas, um handler global de exceções e respostas de erro mais descritivas e consistentes para o cliente da API.
