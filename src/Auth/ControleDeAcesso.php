<?php
namespace Microblog\Auth;

/* Sessões no PHP
Funcionalidade usada para conttrolar o acesso a determinadas
páginas e recursos de um site/aplicação.
   
Exemplos: area administrativa, painel de controle, área de cliente/aluno.

Nessas areas, o acesso depende de alguma forma de autenticação, com login e
senha, biometria, por dois fatores etc. */


final class ControleDeAcesso
{
    //Impedir a criação de objeto/instancia
    private function __construct() {}

    //Inicia a sessão se ela ainda não estiver ativa
    private static function iniciarSessao(): void
    {
        //Se o status da sessão NÃO FOR ativa (ou, se a sessão está inativa)
       if(session_status() !== PHP_SESSION_ACTIVE){
        //Então iniciamos a sessão
          session_start();
       }
    }

    private static function encerrarSessao() : void
    {
        $_SESSION = [];  //limpar os dados do usuario armazenados na sessão
        session_destroy();  //destroi/ remove quaisquer dados da sessão no navegador   
    }
    
    public static function exigirLogin(): void
    {
        self::iniciarSessao();

        //se NÃO EXISTIR na sessão uma variável chamada id,
        //significa que o usuário NÃO ESTÁ logado
        if(!isset($_SESSION["id"])){
            //encerrar a sessão 
            self::encerrarSessao();

            //redirecionar para a página de login
            header("location:../login.php?acesso_proibido");
            exit;
        }
    }

    public static function login(int $id, string $nome, string $tipo): void
    {
       self::iniciarSessao();

       //Cria as variáveis de sessão com os dados de quem está logando 
       $_SESSION['id'] = $id;
       $_SESSION['nome'] = $nome;
       $_SESSION['tipo'] = $tipo;
    }

    public static function logout(): void
    {
        self::iniciarSessao();
        self::encerrarSessao();
        header("location:../login.php?logout");
        exit;
    }
}