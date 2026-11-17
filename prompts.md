# Prompts

## Atualização de notícias

Analise a classe NoticiaServico e a classe Noticia, além das páginas administrativas já existentes.

Identifique os padrões utilizados no projeto para implementar a atualização de notícias.

Crie na classe NoticiaServico o método necessário para atualizar uma notícia, seguindo o padrão dos métodos existentes.

Programe a página admin/noticia-atualiza.php para permitir a edição dos dados da notícia selecionada.

Atenção: os campos devem carregar os dados atuais da notícia, incluindo o select de categorias com a categoria correspondente selecionada.

Respeite as permissões de ADMIN e EDITOR, garantindo que o editor só consiga atualizar suas próprias notícias.

Integre a funcionalidade ao link existente na página admin/noticias.php.

Após implementar, teste a atualização e corrija possíveis erros.

Crie o arquivo prompts.md e
registre este prompt no arquivo prompts.md.
Caso ocorra algum erro corrija

## Exclusão de notícias

Analise a classe NoticiaServico.php e a classe Noticia, além das páginas administrativas já existentes.
 
Identifique os padrões utilizados no projeto para implementar a exclusão de notícias.
 
Crie na classe NoticiaServico.php o método necessário para excluir uma notícia, seguindo o padrão dos métodos existentes.
 
Programe a página admin/noticia-exclui.php para permitir a exclusão da notícia selecionada.
 
Respeite as permissões de ADMIN e EDITOR, garantindo que:
O ADMIN consiga excluir qualquer notícia.
O EDITOR consiga excluir somente as próprias notícias.
O sistema impeça que um editor exclua notícias de outros usuários.
Integre a funcionalidade ao link ou botão de exclusão existente na página admin/noticias.php.
Caso encontre erros, faça as correções necessárias.
Verifique se não existem problemas nas demais funcionalidades .

No arquivo prompts.md registre este prompt nele.
 
Caso ocorra algum erro corrija antes de finalizar.

## Categorias dinâmicas no menu

Analise a classe responsável pelas categorias, a classe NoticiaServico e o arquivo includes/cabecalho.php.

Identifique os métodos existentes para listar todas as categorias cadastradas no banco de dados.

Programe o arquivo includes/cabecalho.php para fazer com que o menu do site exiba dinamicamente todas as categorias existentes.

Cada categoria deve possuir um link dinâmico para a página noticias-por-categoria.php.
O link deve enviar o ID da categoria selecionada.

Utilize os métodos existentes nas classes do projeto, e evite duplicação de código.
Respeite a estrutura e os padrões já existentes.

No arquivo prompts.php, registe esse prompt sem apagar os outros.

## Notícias dinâmicas na página inicial

Analise a classe NoticiaServico, a classe Noticia e os arquivos index.php e includes/todas.php.

Identifique os métodos existentes para listar as notícias em destaque e todas as notícias cadastradas no banco de dados.

Programe a página index.php para exibir as notícias marcadas como destaque, mostrando a imagem, o titulo e o resumo.

Configure o link dinâmico para abrir a página noticia.php quando o usuário clicar em uma notícia, enviando o ID correspondente.

Programe o arquivo includes/todas.php para realizar a exibição de todas as notícias cadastradas no banco de dados.

A exibição das notícias em destaque deve ficar na index.php.
A exibição de todas as notícias deve ser feita no arquivo includes/todas.php.

Respeite o layout.
Utilize os métodos existentes na classe NoticiaServico.php sempre que possível.
Caso encontre erros, faça as correções necessárias.
No arquivo prompts.md registre este prompt, sem apagar os outros prompts existentes.
