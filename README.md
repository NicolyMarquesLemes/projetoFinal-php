# 📰 Portal de Notícias em PHP

## 📌 Sobre o Projeto

Este é um sistema completo de **Portal de Notícias** desenvolvido em **PHP + MySQL**, com autenticação de usuários e funcionalidades de CRUD (Criar, Ler, Atualizar e Excluir notícias).

O sistema permite que usuários se cadastrem, façam login e publiquem notícias com imagem, além de editar ou excluir suas próprias postagens.

---

## 🚀 Funcionalidades

### 🔐 Autenticação

* Cadastro de usuários
* Login com validação de senha
* Logout
* Edição de perfil
* Exclusão de conta

### 📰 Notícias

* Criar nova notícia
* Listar notícias
* Visualizar notícia completa
* Editar notícia (somente autor)
* Excluir notícia (somente autor)
* Upload de imagem

### 🌎 Extras

* Filtro de notícias por país
* Exibição de bandeiras por país
* Interface moderna e responsiva

---

## 🛠️ Tecnologias Utilizadas

* PHP (Programação backend)
* MySQL (Banco de dados)
* HTML5
* CSS3
* Sessões (Session)

---

## 📁 Estrutura do Projeto

```
/portal-noticias
│
├── backend/
│   ├── conexao.php
│   └── verifica_login.php
│
├── dao/
│   ├── NoticiaDAO.php
│   └── UsuarioDAO.php
│
├── private/
│   ├── nova_noticia.php
│   ├── editar_noticia.php
│   └── excluir_noticia.php
│
├── usuarios/
│   ├── editar_usuario.php
│   └── excluir_usuario.php
│
├── imagens/
├── bandeiras/
│
├── index.php
├── login.php
├── cadastro.php
├── logout.php
├── noticia.php
├── style.css
```

---

## ⚙️ Configuração do Projeto

### 1. Banco de Dados

Crie um banco chamado:

```
portal_noticias
```

### 2. Tabelas necessárias

#### 👤 Tabela `usuarios`

```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255)
);
```

#### 📰 Tabela `noticias`

```sql
CREATE TABLE noticias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255),
    noticia TEXT,
    data DATETIME,
    autor INT,
    imagem VARCHAR(255),
    pais VARCHAR(100),
    FOREIGN KEY (autor) REFERENCES usuarios(id)
);
```

---

### 3. Configurar conexão

Arquivo:

```
backend/conexao.php
```

Edite se necessário:

```php
$host = "localhost";
$user = "root";
$pass = "";
$db = "portal_noticias";
```

---

## ▶️ Como Executar

1. Coloque o projeto na pasta do servidor (ex: `htdocs` do XAMPP)
2. Inicie o Apache e MySQL
3. Acesse no navegador:

```
http://localhost/portal-noticias
```

---

## 🔒 Regras do Sistema

* Apenas usuários logados podem criar notícias
* Apenas o autor pode editar ou excluir sua notícia
* Senhas são armazenadas com **hash seguro**
* Validação de campos obrigatórios

---

## 🎨 Interface

* Layout moderno com CSS
* Botões com efeitos hover
* Formulários estilizados
* Cards para exibição das notícias
* Tela de login e cadastro centralizadas

---

## 📌 Possíveis Melhorias

* Sistema de comentários
* Curtidas nas notícias
* Paginação
* Busca por palavras-chave
* Upload com validação de tipo de imagem
* Painel administrativo

---

## 👨‍💻 Autor

Projeto desenvolvido para fins de estudo e prática em PHP e desenvolvimento web.

---
