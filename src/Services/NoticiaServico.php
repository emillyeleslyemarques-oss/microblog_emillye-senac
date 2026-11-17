<?php
namespace Microblog\Services;

use Exception;
use Microblog\Database\ConexaoBD;
use Microblog\Enums\Destaque;
use Microblog\Enums\TipoUsuario;
use Microblog\Helpers\Utils;
use Microblog\Models\Noticia;
use PDO;
use PDOException;

final class NoticiaServico
{
    private PDO $conexao;

    public function __construct()
    {
        $this->conexao = ConexaoBD::getConexao();
    }

    public function listarTodos(TipoUsuario $tipoUsuario, int $usuarioId): array
    {
        if ($tipoUsuario === TipoUsuario::ADMIN) {
            $sql = "SELECT noticias.id, noticias.titulo, noticias.data, noticias.destaque,
                           usuarios.nome AS autor
                    FROM noticias
                    LEFT JOIN usuarios ON noticias.usuario_id = usuarios.id
                    ORDER BY noticias.data DESC";

            try {
                $consulta = $this->conexao->query($sql);
                return $consulta->fetchAll();
            } catch (PDOException $e) {
                Utils::registrarLog($e);
                throw new Exception("Erro ao listar notícias");
            }
        }

        $sql = "SELECT id, titulo, data, destaque
                FROM noticias
                WHERE usuario_id = :usuario_id
                ORDER BY data DESC";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":usuario_id", $usuarioId);
            $consulta->execute();
            return $consulta->fetchAll();
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao listar notícias");
        }
    }

    public function listarDestaques(): array
    {
        return $this->listarParaExibicao(Destaque::SIM);
    }

    public function listarTodasPublicas(): array
    {
        return $this->listarParaExibicao();
    }

    public function listarPorCategoria(int $categoriaId): array
    {
        $sql = "SELECT noticias.id, noticias.titulo, noticias.data, noticias.resumo,
                       usuarios.nome AS autor
                FROM noticias
                LEFT JOIN usuarios ON noticias.usuario_id = usuarios.id
                WHERE noticias.categoria_id = :categoria_id
                ORDER BY noticias.data DESC";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":categoria_id", $categoriaId);
            $consulta->execute();
            return $consulta->fetchAll();
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao listar notícias por categoria");
        }
    }

    public function pesquisarPorTermo(string $termo): array
    {
        $termoPesquisado = "%" . $termo . "%";
        $sql = "SELECT id, titulo
                FROM noticias
                WHERE titulo LIKE :titulo
                   OR resumo LIKE :resumo
                   OR texto LIKE :texto
                ORDER BY data DESC";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":titulo", $termoPesquisado);
            $consulta->bindValue(":resumo", $termoPesquisado);
            $consulta->bindValue(":texto", $termoPesquisado);
            $consulta->execute();
            return $consulta->fetchAll();
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao pesquisar notícias");
        }
    }

    private function listarParaExibicao(?Destaque $destaque = null): array
    {
        $sql = "SELECT id, data, titulo, resumo, imagem
                FROM noticias";

        if ($destaque !== null) {
            $sql .= " WHERE destaque = :destaque";
        }

        $sql .= " ORDER BY data DESC";

        try {
            $consulta = $this->conexao->prepare($sql);

            if ($destaque !== null) {
                $consulta->bindValue(":destaque", $destaque->value);
            }

            $consulta->execute();
            return $consulta->fetchAll();
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao listar notícias");
        }
    }

    public function buscarPorId(int $id, TipoUsuario $tipoUsuario, int $usuarioId): ?array
    {
        return $this->buscarNoticiaPorId($id, $tipoUsuario, $usuarioId);
    }

    public function buscarParaExibicaoPorId(int $id): ?array
    {
        return $this->buscarNoticiaPorId($id, null, null);
    }

    private function buscarNoticiaPorId(int $id, ?TipoUsuario $tipoUsuario, ?int $usuarioId): ?array
    {
        $sql = "SELECT noticias.id, noticias.data, noticias.titulo, noticias.texto,
                       noticias.resumo, noticias.imagem, noticias.destaque,
                       noticias.usuario_id, noticias.categoria_id, usuarios.nome AS autor
                FROM noticias
                LEFT JOIN usuarios ON noticias.usuario_id = usuarios.id
                WHERE noticias.id = :id";

        if ($tipoUsuario !== null && $tipoUsuario !== TipoUsuario::ADMIN) {
            $sql .= " AND noticias.usuario_id = :usuario_id";
        }

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $id);

            if ($tipoUsuario !== null && $tipoUsuario !== TipoUsuario::ADMIN) {
                $consulta->bindValue(":usuario_id", $usuarioId);
            }

            $consulta->execute();
            return $consulta->fetch() ?: null;
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao buscar notícia por ID.");
        }
    }

    public function atualizar(Noticia $noticia, TipoUsuario $tipoUsuario, int $usuarioId): void
    {
        $sql = "UPDATE noticias
                SET titulo = :titulo,
                    texto = :texto,
                    resumo = :resumo,
                    imagem = :imagem,
                    destaque = :destaque,
                    categoria_id = :categoria_id
                WHERE id = :id";

        if ($tipoUsuario !== TipoUsuario::ADMIN) {
            $sql .= " AND usuario_id = :usuario_id";
        }

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":titulo", $noticia->getTitulo());
            $consulta->bindValue(":texto", $noticia->getTexto());
            $consulta->bindValue(":resumo", $noticia->getResumo());
            $consulta->bindValue(":imagem", $noticia->getImagem());
            $consulta->bindValue(":destaque", $noticia->getDestaque()->value);
            $consulta->bindValue(":categoria_id", $noticia->getCategoriaId());
            $consulta->bindValue(":id", $noticia->getId());

            if ($tipoUsuario !== TipoUsuario::ADMIN) {
                $consulta->bindValue(":usuario_id", $usuarioId);
            }

            $consulta->execute();
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao atualizar notícia");
        }
    }

    public function inserir(Noticia $noticia): void
    {
        $sql = "INSERT INTO noticias (
            titulo, texto, resumo, imagem, destaque, usuario_id, categoria_id
        ) VALUES(
            :titulo, :texto, :resumo, :imagem, :destaque, :usuario_id, :categoria_id
        )";

        try {
            $consulta = $this->conexao->prepare($sql);

            $consulta->bindValue(":titulo", $noticia->getTitulo());
            $consulta->bindValue(":texto", $noticia->getTexto());
            $consulta->bindValue(":resumo", $noticia->getResumo());
            $consulta->bindValue(":imagem", $noticia->getImagem());
            $consulta->bindValue(":destaque", $noticia->getDestaque()->value);
            $consulta->bindValue(":usuario_id", $noticia->getUsuarioId());
            $consulta->bindValue(":categoria_id", $noticia->getCategoriaId());

            $consulta->execute();

        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao inserir notícia");
        }        
    }

    public function excluir(int $id, TipoUsuario $tipoUsuario, int $usuarioId): void
    {
        $sql = "DELETE FROM noticias WHERE id = :id";

        if ($tipoUsuario !== TipoUsuario::ADMIN) {
            $sql .= " AND usuario_id = :usuario_id";
        }

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindValue(":id", $id);

            if ($tipoUsuario !== TipoUsuario::ADMIN) {
                $consulta->bindValue(":usuario_id", $usuarioId);
            }

            $consulta->execute();
        } catch (PDOException $e) {
            Utils::registrarLog($e);
            throw new Exception("Erro ao excluir notícia");
        }
    }
}