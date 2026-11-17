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

## Exibição dinâmica de notícia

Analise a classe NoticiaServico, a classe Noticia e a estrutura atual da página noticia.php.

Identifique os métodos existentes para buscar uma notícia específica pelo seu ID.

Programe a página noticia.php para exibir os detalhes completos da notícia selecionada.

Devem ser exibidos:
- Data da publicação, formatada para dia, mês e ano.
- Autor da notícia.
- Imagem da notícia.
- Texto completo da notícia.

A notícia deve ser identificada pelo ID recebido através da URL.
Caso a notícia não tenha um autor associado, exiba o texto "Equipe Microblog".
Utilize os métodos existentes na classe NoticiaServico.
Trate situações em que o ID não seja informado ou a notícia não seja encontrada.
Respeite os padrões que já existem no projeto.
Caso encontre erros, faça as correções necessárias.

No arquivo prompts.md registre este prompt, sem apagar os registros anteriores.
Cada vez que for fazendo o prompt, coloque todas as informações que voce registrou e modificou no arquivo AGENTS.md.

## Notícias por categoria

Analise o arquivo noticias-por-categoria.php, a classe CategoriaServico, a classe NoticiaServico e a classe Noticia.
Programe essa página para funcionar dinamicamente, utilizando os dados cadastrados no banco de dados.

Receba o ID da categoria através do parâmetro GET da URL.
Utilize a classe CategoriaServico para localizar a categoria selecionada.
Utilize a classe NoticiaServico para buscar as notícias pertencentes à categoria selecionada.
Para cada notícia, exiba título, data formatada para dia/mês/ano, autor e resumo.
Caso a notícia não tenha um autor associado, exiba "Equipe Microblog".
Configure o link dinâmico para abrir noticia.php enviando o ID da notícia selecionada.
Remova os conteúdos provisórios que estão escritos diretamente no HTML.
Trate situações em que o ID não seja informado, a categoria não exista ou não possua notícias cadastradas.
No arquivo prompts.md registre este prompt, sem apagar os registros anteriores.
