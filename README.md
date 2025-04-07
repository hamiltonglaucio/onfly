
# 🚀 Desafio Técnico - Onfly - Sistema de Pedidos de Viagem

Este projeto implementa dois microsserviços usando Laravel 12 com Clean Architecture, JWT e testes automatizados. A proposta segue os requisitos fornecidos no teste técnico da Onfly.

## 🧱 Estrutura

- **AuthService**: serviço de autenticação
- **TravelService**: serviço para requisição de viagens
- Ambos os serviços estão organizados em `app/AuthService` e `app/TravelService`

---

## ✅ Tecnologias Utilizadas

- Laravel 12 + Laravel Sail
- MySQL
- JWT Auth (tymon/jwt-auth)
- Scribe (documentação da API)
- PHPUnit (testes)
- Docker e Docker Compose

---

## ⚙️ Requisitos

- Docker
- Docker Compose
- PHP 8.2+ (se quiser rodar sem Docker)

---

## 📦 Estrutura do Projeto

```
.
├── app
│   ├── AuthService
│   │   ├── Domain
│   │   │   ├── Contracts
│   │   │   ├── Entities
│   │   │   └── ValueObjects
│   │   ├── Infrastructure
│   │   │   ├── Database
│   │   │   ├── Eloquent
│   │   │   ├── Mappers
│   │   │   ├── Middleware
│   │   │   ├── Providers
│   │   │   └── Repositories
│   │   ├── UseCases
│   │   │   └── RegisterUser.php
│   │   └── UserInterface
│   │       ├── Controllers
│   │       ├── Requests
│   │       └── Routes
│   └── TravelService
│       ├── Domain
│       │   ├── Contracts
│       │   └── Entities
│       ├── Infrastructure
│       │   ├── Database
│       │   ├── Eloquent
│       │   ├── Mappers
│       │   ├── Middleware
│       │   ├── Providers
│       │   └── Repositories
│       ├── UseCases
│       │   └── ...
│       └── UserInterface
│           ├── Controllers
│           ├── Requests
│           └── Routes
├── tests
│   └── Unit
│       └── ... (testes de UseCases)
...
```

---

## 🧪 Instalação e Execução

### 1. Clonar o repositório

```bash
git clone https://github.com/hamiltonglaucio/onfly.git
cd onfly
```

### 2. Instalar dependências

```bash
composer install
```

### 3. Copiar o .env

```bash
cp .env.example .env
```

### 4. Subir os containers

```bash
./vendor/bin/sail up -d
```

### 5. Rodar migrations e seeders

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

- Todas as migrations dos serviços são detectadas automaticamente.
- Os seeders criam usuários de teste com senha: `123456`

### 6. Gerar documentação da API

```bash
./vendor/bin/sail artisan scribe:generate
```

---

## 🔐 Autenticação JWT

### Obter token de acesso

```http
POST /api/auth/login
```

Payload:

```json
{
  "email": "usuario@email.com",
  "password": "123456"
}
```

Use o token retornado no header das demais requisições:

```
Authorization: Bearer {token}
```

---

## 🧾 Documentação da API

Gerada com [Scribe](https://scribe.knuckles.wtf/laravel).

Acesse após gerar:

```
http://localhost/docs
```

Inclui exemplos de uso, autenticação, cabeçalhos, rotas e respostas esperadas.

---

## 🔍 Testes Automatizados

Os testes estão localizados em `tests/Unit` e seguem a estrutura de testar diretamente os **casos de uso (UseCases)**. Essa abordagem centraliza o teste no núcleo da regra de negócio da aplicação.

### Executar todos os testes:

```bash
./vendor/bin/sail test
```

### Executar teste específico:

```bash
./vendor/bin/sail test --filter=NomeDoTeste
```

Exemplo:

```bash
./vendor/bin/sail test --filter=CreateTravelRequestTest
```

---

## ▶️ Executar o projeto rapidamente

```bash
# 1. Clonar
git clone https://github.com/hamiltonglaucio/onfly.git
cd onfly

# 2. Instalar dependências
composer install

# 3. Copiar o .env
cp .env.example .env

# 4. Subir containers
./vendor/bin/sail up -d

# 5. Migrations + seeders
./vendor/bin/sail artisan migrate:fresh --seed

# 6. Documentação da API
./vendor/bin/sail artisan scribe:generate
```

---

## 👨‍💻 Autor

**Hamilton Gláucio de Oliveira Júnior**  
📱 WhatsApp: (87) 98115-3286  
✉️ E-mail: contato@hamiltonjunior.com.br
