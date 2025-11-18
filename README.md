# MyFitSystem - Sistema de Gestão de Academias

Sistema SaaS completo para gestão de academias, permitindo que academias gerenciem seus alunos, pagamentos, treinos, aulas, desempenho dos alunos e muito mais.

## 📋 Índice

- [Características](#-características)
- [Tipos de Usuários](#-tipos-de-usuários)
- [Requisitos](#-requisitos)
- [Instalação](#-instalação)
- [Configuração](#-configuração)
- [Uso](#-uso)
- [Credenciais Padrão](#-credenciais-padrão)
- [Estrutura do Projeto](#-estrutura-do-projeto)
- [Tecnologias Utilizadas](#-tecnologias-utilizadas)
- [Contribuindo](#-contribuindo)

## ✨ Características

### Funcionalidades Principais

- **Multi-tenancy**: Suporte completo para múltiplas academias
- **Gestão de Alunos**: Cadastro, acompanhamento e histórico completo
- **Sistema de Treinos**: Criação e acompanhamento de treinos personalizados
- **Agendamento de Aulas**: Sistema completo de agendamento e reservas
- **Controle de Pagamentos**: Gestão de contratos e pagamentos de alunos e estabelecimentos
- **Avaliações Físicas**: Acompanhamento de evolução física dos alunos
- **Sistema de Metas**: Definição e acompanhamento de metas dos alunos
- **Conquistas**: Sistema gamificado de conquistas e achievements
- **Notificações**: Sistema completo de notificações para usuários e alunos
- **Dashboard Analítico**: Métricas e indicadores para gestores
- **PWA**: Interface mobile-first para alunos via Progressive Web App

### Recursos Avançados

- Multi-estabelecimento: Usuários e alunos podem estar vinculados a múltiplas academias
- Controle de permissões por role e estabelecimento
- Relatórios e estatísticas detalhadas
- Interface responsiva e moderna
- Otimizações de performance com queries eficientes

## 👥 Tipos de Usuários

### 1. Superuser (Dono da Plataforma)
- **Acesso**: `/gestao`
- **Permissões**: 
  - Visualização completa de todos os módulos
  - Acesso a dados de todas as academias
  - Dashboard com métricas globais
  - Controle de pagamentos de acesso ao sistema

### 2. Admin (Proprietário/Gestor da Academia)
- **Acesso**: `/gestao`
- **Permissões**:
  - Gestão completa da sua academia
  - Visualização de métricas e receitas
  - Gerenciamento de alunos, treinos e aulas
  - Controle de equipe (instrutores, recepcionistas)
  - Dashboard com dados da academia

### 3. Aluno (Usuário Final)
- **Acesso**: `/app`
- **Funcionalidades**:
  - Visualização e execução de treinos
  - Agendamento de aulas
  - Acompanhamento de progresso e metas
  - Visualização de conquistas
  - Interface mobile-first (PWA)

### Sub-roles do Admin
- **Instrutor**: Gestão de treinos e aulas
- **Recepcionista**: Atendimento e agendamentos
- **Assistente**: Apoio administrativo
- **Nutricionista**: Acompanhamento nutricional

## 📦 Requisitos

- PHP >= 8.1
- Composer
- Node.js >= 16.x e NPM
- MySQL >= 5.7 ou MariaDB >= 10.2
- Servidor web (Apache/Nginx) ou PHP Built-in Server
- Extensões PHP: BCMath, Ctype, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

## 🚀 Instalação

### 1. Clone o repositório

```bash
git clone <repository-url>
cd myfitsystem
```

### 2. Instale as dependências do PHP

```bash
composer install
```

### 3. Configure o ambiente

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure o banco de dados

Edite o arquivo `.env` e configure as credenciais do banco de dados:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=myfitsystem
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 5. Execute as migrations

```bash
php artisan migrate
```

### 6. Popule o banco de dados com dados iniciais

```bash
php artisan db:seed
```

### 7. Instale as dependências do NPM

```bash
npm install
```

### 8. Compile os assets

Para desenvolvimento:
```bash
npm run dev
```

Para produção:
```bash
npm run build
```

### 9. Inicie o servidor de desenvolvimento

```bash
php artisan serve
```

O sistema estará disponível em: `http://localhost:8000`

## ⚙️ Configuração

### Configuração de Email

Configure suas credenciais de email no arquivo `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu_email@gmail.com
MAIL_PASSWORD=sua_senha
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@myfitsystem.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Configuração de Storage

Certifique-se de que o link simbólico para storage está criado:

```bash
php artisan storage:link
```

### Configuração de Cache

Para produção, configure o cache:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🎮 Uso

### Acessando o Sistema

1. **Superuser/Admin**: Acesse `/gestao/login`
2. **Aluno**: Acesse `/app/login`

### Primeiros Passos

1. Faça login com uma das credenciais padrão (veja abaixo)
2. Se for admin ou aluno com múltiplas academias, selecione o estabelecimento
3. Explore os dashboards e funcionalidades disponíveis

### Comandos Úteis

```bash
# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recriar banco de dados (cuidado: apaga todos os dados)
php artisan migrate:fresh --seed

# Executar seeders específicos
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=EstablishmentSeeder

# Compilar assets para produção
npm run build

# Verificar rotas
php artisan route:list
```

## 🔑 Credenciais Padrão

Após executar `php artisan db:seed`, as seguintes credenciais estarão disponíveis:

### Superuser
- **Email**: `superuser@myfitsystem.com`
- **Senha**: `password`
- **Acesso**: `/gestao/login`

### Admin (Academia Fitness Center)
- **Email**: `admin0@academia0.com`
- **Senha**: `password`
- **Acesso**: `/gestao/login`

### Aluno de Exemplo
- **Email**: `joao@example.com`
- **Senha**: `password`
- **Acesso**: `/app/login`

### Outros Alunos
- **Email**: `maria@example.com` / **Senha**: `password`
- **Email**: `pedro@example.com` / **Senha**: `password`
- **Email**: `ana@example.com` / **Senha**: `password`
- **Email**: `carlos@example.com` / **Senha**: `password`

## 📁 Estrutura do Projeto

```
myfitsystem/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Controllers da aplicação
│   │   ├── Middleware/       # Middlewares customizados
│   │   ├── Requests/         # Form Requests de validação
│   │   └── Traits/           # Traits reutilizáveis (HasEstablishmentContext)
│   ├── Models/               # Modelos Eloquent
│   ├── Services/             # Serviços de negócio
│   │   ├── DashboardService.php
│   │   ├── PaymentService.php
│   │   ├── WorkoutService.php
│   │   ├── NotificationService.php
│   │   ├── GoalService.php
│   │   └── AchievementService.php
│   └── Jobs/                 # Jobs assíncronos
├── database/
│   ├── migrations/           # Migrations do banco de dados
│   ├── seeders/              # Seeders de dados iniciais
│   └── factories/            # Factories para testes
├── resources/
│   ├── views/                # Templates Blade
│   │   ├── admin/            # Views administrativas
│   │   ├── student/           # Views para alunos
│   │   └── layouts/          # Layouts base
│   ├── js/                   # JavaScript (Alpine.js, Vite)
│   └── css/                  # Estilos (Tailwind CSS)
├── routes/
│   └── web.php               # Rotas da aplicação
└── public/                   # Arquivos públicos
```

## 🛠️ Tecnologias Utilizadas

### Backend
- **Laravel 11.x**: Framework PHP
- **MySQL/MariaDB**: Banco de dados
- **Spatie Laravel Permission**: Gerenciamento de permissões

### Frontend
- **Tailwind CSS**: Framework CSS utility-first
- **Alpine.js**: Framework JavaScript minimalista
- **Vite**: Build tool moderna
- **Flowbite**: Componentes UI para Tailwind

### Outras Tecnologias
- **Carbon**: Manipulação de datas
- **PWA**: Progressive Web App capabilities

## 📝 Seeders Disponíveis

O sistema inclui os seguintes seeders:

- **RoleSeeder**: Cria os roles do sistema (superuser, admin, instrutor, etc.)
- **AchievementSeeder**: Cria as conquistas disponíveis
- **EstablishmentSeeder**: Cria estabelecimentos de exemplo
- **UserSeeder**: Cria usuários (superuser, admins, instrutores, recepcionistas)
- **ModalitySeeder**: Cria modalidades de aulas
- **CategorySeeder**: Cria categorias de exercícios
- **StudentSeeder**: Cria alunos de exemplo

Execute todos os seeders:
```bash
php artisan db:seed
```

## 🔒 Segurança

- Autenticação multi-guard (user e student)
- Middleware de verificação de roles e estabelecimentos
- Proteção CSRF em todos os formulários
- Validação de dados em todos os endpoints
- Soft deletes para preservação de dados
- Hash de senhas com bcrypt

## 🚧 Funcionalidades Futuras

- [ ] Integração com APIs de pagamento
- [ ] Sistema de chat/mensagens
- [ ] App mobile nativo
- [ ] Relatórios avançados com gráficos
- [ ] Sistema de comissões para instrutores
- [ ] Exportação de dados (PDF/Excel)
- [ ] API REST para integrações

## 🤝 Contribuindo

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto é proprietário. Todos os direitos reservados.

## 📞 Suporte

Para suporte, entre em contato através do email: suporte@myfitsystem.com

---

**Desenvolvido com ❤️ para gestão de academias**
