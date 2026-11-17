# Guia de desenvolvimento — Microblog

Este arquivo registra a estrutura e as convenções observadas no projeto para orientar futuras alterações, inclusive em outros ambientes de desenvolvimento.

## Visão geral

Microblog é um portal didático de notícias em PHP, com área pública e painel administrativo, usando orientação a objetos e MySQL/MariaDB por PDO.

- A raiz contém as páginas públicas: `index.php`, `noticia.php`, `noticias-por-categoria.php`, `resultados.php` e `login.php`.
- `admin/` contém as páginas do painel e operações administrativas.
- `src/` contém as classes da aplicação, organizadas por namespace e responsabilidade.
- `includes/` contém cabeçalhos, rodapés e outros trechos compartilhados.
- `images/`, `css/`, `js/` e `admin/js/` contêm recursos estáticos.
- `_materiais/` contém scripts SQL, imagens e materiais de apoio.
- `logs/erros.log` é o destino dos registros de erro da aplicação.

## Ambiente e execução local

- O projeto foi desenvolvido para PHP e MySQL/MariaDB, e pode ser executado localmente com Apache via XAMPP.
- O `README.md` documenta PHP 8.5 ou superior e as extensões `pdo_mysql`, `mbstring` e `fileinfo`. As enums usadas pelo código requerem PHP 8.1 ou superior.
- O banco esperado pela aplicação é `microblog_2026`. Os scripts de referência estão em `_materiais/microblog_2026_inicial.sql` e `_materiais/microblog_2026_completo.sql`.
- A conexão e as credenciais de desenvolvimento estão definidas diretamente em `src/Database/ConexaoBD.php` (host `localhost`, usuário `root`, senha vazia). Ajuste-as ao ambiente local quando necessário; não inclua credenciais particulares em alterações versionadas.
- O Composer mapeia `Microblog\\` para `src/`. Se o autoload estiver desatualizado ou `vendor/` não existir, execute `composer dump-autoload` na raiz do projeto. `vendor/` é ignorado pelo Git.
- O arquivo `_materiais/modelagem-fisica.md` ainda cita `microblog_2025`; para a aplicação e scripts atuais, confira `microblog_2026` e os SQL em `_materiais/`.

## Arquitetura PHP

O autoload PSR-4 está configurado em `composer.json`. Os namespaces seguem a estrutura de pastas:

- `Microblog\Auth`: controle de sessão e autorização.
- `Microblog\Database`: conexão PDO compartilhada.
- `Microblog\Enums`: `Destaque` (`sim`, `nao`) e `TipoUsuario` (`admin`, `editor`).
- `Microblog\Helpers`: normalização, escape, upload, logs e validações.
- `Microblog\Models`: `Categoria`, `Noticia` e `Usuario`.
- `Microblog\Services`: consultas e operações de persistência.

Convenções existentes:

- Classes e enums em PascalCase; métodos, propriedades e variáveis em camelCase.
- Nomes de domínio e código predominantemente em português; tabelas e colunas usam os nomes definidos no esquema SQL (por exemplo, `usuario_id` e `categoria_id`).
- Modelos usam propriedades privadas tipadas e getters. Modelos não validam os dados por conta própria: as páginas chamam `Validacoes` antes de persistir.
- Serviços são responsáveis pelo SQL e recebem modelos nas operações de inserção/atualização. Consultas geralmente retornam arrays associativos do PDO, e não modelos.
- Serviços obtêm a conexão com `ConexaoBD::getConexao()`, usam `PDO::prepare()` e parâmetros vinculados nas consultas com entrada variável.
- Mantenha nomes e assinaturas em português e respeite os namespaces e o autoload existentes ao adicionar classes.

## Fluxos e regras de acesso

- `ControleDeAcesso::exigirLogin()` protege páginas autenticadas; `exigirAdmin()` restringe operações administrativas.
- O cabeçalho `includes/cabecalho-admin.php` também exige login. Páginas que exigem administrador devem chamar explicitamente `exigirAdmin()`.
- O código atual permite que editores gerenciem as próprias notícias e o perfil; administradores podem listar notícias de todos os autores e gerenciar usuários e categorias. A listagem de notícias recebe o tipo e o ID do usuário para aplicar esse escopo.
- `admin/usuarios.php` e as páginas de usuários e categorias exigem administrador. Embora o `README.md` descreva categorias como disponíveis também ao editor, o comportamento efetivo das páginas atuais é somente para administradores.
- O login localiza o usuário por e-mail, verifica a senha com `password_verify()` e cria a sessão; senhas novas são codificadas com `password_hash()`.

## Tratamento de entrada, validação e saída

- Use `Utils::normalizarEntrada()` para entradas de formulário e parâmetros de URL. O modo padrão e o modo `email` exigem string e aplicam `trim`; o modo `inteiro` valida inteiros e devolve `0` para valores inválidos.
- Use `Utils::verificarId()` para validar IDs positivos recebidos pela URL antes de consultar ou excluir registros.
- Use `Validacoes` para regras existentes de nome, e-mail, senha, tipo de usuário, título, texto, resumo, destaque e categoria. Erros de validação são lançados como `InvalidArgumentException`.
- A normalização não substitui escape nem validação. Escape valores ao renderizá-los em HTML usando `Utils::escaparHtml()`.
- Erros técnicos devem ser registrados por `Utils::registrarLog()` e apresentados com mensagem genérica; não exponha detalhes de PDO ou dados sensíveis no HTML.
- Uploads de notícia passam por `Utils::upload()`, que valida o arquivo, tamanho, MIME e extensão antes de movê-lo para `images/`.
- O resumo é limitado a 300 caracteres pela validação e pelo formulário. Os scripts SQL atuais declaram a coluna como `VARCHAR(500)`; mantenha em mente que a regra da aplicação é mais restritiva.

## Estado das funcionalidades administrativas

- Usuários: listagem, inserção, busca por ID/e-mail, atualização e exclusão estão presentes nos serviços e páginas. A atualização mantém a senha existente quando o campo de senha fica em branco.
- Categorias: listagem, inserção, busca por ID, atualização e exclusão estão presentes.
- Notícias: `NoticiaServico::listarTodos()` lista notícias no escopo de ADMIN/EDITOR; `listarDestaques()` e `listarTodasPublicas()` alimentam a página inicial e a listagem pública. `buscarPorId()` aplica escopo de ADMIN/EDITOR, enquanto `buscarParaExibicaoPorId()` busca uma notícia pelo ID para exibição pública e inclui o autor por `LEFT JOIN`.
- `index.php` exibe notícias em destaque; `includes/todas.php` exibe todas as notícias cadastradas. Os links levam a `noticia.php` com o ID correspondente.
- `noticia.php` valida o ID recebido pela URL, mostra data em dia/mês/ano, autor, imagem e texto completo. ID inválido ou ausente redireciona para `index.php`; ID válido sem registro exibe “Notícia não encontrada”. Notícias sem usuário associado usam “Equipe Microblog” como autor.
- `includes/cabecalho.php` usa `CategoriaServico::listarTodos()` para compor o menu de categorias e gerar links com os respectivos IDs.
- `noticias-por-categoria.php` valida o ID recebido por GET (ID ausente ou inválido redireciona para `index.php`), busca a categoria com `CategoriaServico::buscarPorId()` e suas notícias com `NoticiaServico::listarPorCategoria()`. Mostra título, data, autor e resumo; usa “Equipe Microblog” sem autor e informa quando a categoria não existe ou não possui notícias. A categoria selecionada fica em `$categoriaSelecionada` para evitar colisão com `$categoria`, usado no loop de categorias do cabeçalho incluído.
- A busca do cabeçalho envia o termo `busca` por POST para `resultados.php`, que retorna JSON e usa `NoticiaServico::pesquisarPorTermo()` para procurar em título, resumo e texto. `js/busca.js` atualiza a lista sem recarregar a página e trata entrada vazia, resultado vazio e falhas de rede.
- `admin/noticia-insere.php` valida campos e imagem; `admin/noticias.php` aplica o escopo por administrador/editor. As páginas administrativas de atualização e exclusão de notícia implementam suas operações respeitando esse escopo.

## Validação de alterações

- O `composer.json` atual não define scripts de testes, lint ou build.
- Para arquivos PHP alterados, execute `php -l caminho/do/arquivo.php` quando o PHP CLI estiver disponível.
- Para mudanças que envolvam banco, formulários, sessão ou upload, faça também uma verificação manual no ambiente local configurado com o esquema SQL correspondente.
- Prefira mudanças pequenas e consistentes com os serviços, helpers e tratamento de erros existentes. Não altere funcionalidades provisórias ou regras de permissão sem confirmar o requisito.
