'use strict';

const campoBusca = document.getElementById('campo-busca');
const caixaResultados = document.getElementById('resultados');
const formularioBusca = document.getElementById('form-busca');

if (campoBusca && caixaResultados) {
    let temporizadorBusca;
    let requisicaoAtual;

    function ocultarResultados() {
        caixaResultados.replaceChildren();
        caixaResultados.hidden = true;
        caixaResultados.setAttribute('aria-busy', 'false');
    }

    function exibirMensagem(mensagem, classe) {
        caixaResultados.replaceChildren();
        const aviso = document.createElement('p');
        aviso.className = classe;
        aviso.textContent = mensagem;
        caixaResultados.append(aviso);
        caixaResultados.hidden = false;
    }

    function exibirResultados(resultados, total) {
        caixaResultados.replaceChildren();

        const titulo = document.createElement('h2');
        titulo.className = 'fs-5';
        titulo.append(document.createTextNode('Resultados: '));

        const quantidade = document.createElement('span');
        quantidade.className = 'badge rounded-pill text-bg-success';
        quantidade.textContent = String(total);
        titulo.append(quantidade);
        caixaResultados.append(titulo);

        const lista = document.createElement('div');
        lista.className = 'list-group';

        if (resultados.length === 0) {
            const vazio = document.createElement('p');
            vazio.className = 'list-group-item text-muted mb-0';
            vazio.textContent = 'Nenhuma notícia encontrada.';
            lista.append(vazio);
        } else {
            resultados.forEach((noticia) => {
                const link = document.createElement('a');
                link.className = 'list-group-item list-group-item-action';
                link.href = `noticia.php?id=${encodeURIComponent(noticia.id)}`;
                link.textContent = noticia.titulo;
                lista.append(link);
            });
        }

        caixaResultados.append(lista);
        caixaResultados.hidden = false;
    }

    async function pesquisar(termo) {
        if (requisicaoAtual) {
            requisicaoAtual.abort();
        }

        const requisicao = new AbortController();
        requisicaoAtual = requisicao;
        caixaResultados.hidden = false;
        caixaResultados.setAttribute('aria-busy', 'true');

        try {
            const resposta = await fetch('resultados.php', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                },
                body: new URLSearchParams({ busca: termo }),
                signal: requisicao.signal,
            });
            const dados = await resposta.json();

            if (!resposta.ok) {
                throw new Error(dados.erro || 'Não foi possível realizar a pesquisa.');
            }

            if (termo !== campoBusca.value.trim()) {
                return;
            }

            exibirResultados(dados.resultados, dados.total);
        } catch (erro) {
            if (erro.name !== 'AbortError' && termo === campoBusca.value.trim()) {
                exibirMensagem('Não foi possível realizar a pesquisa. Tente novamente.', 'alert alert-danger mb-0');
            }
        } finally {
            if (requisicaoAtual === requisicao) {
                requisicaoAtual = null;
                caixaResultados.setAttribute('aria-busy', 'false');
            }
        }
    }

    function iniciarPesquisa() {
        window.clearTimeout(temporizadorBusca);
        const termo = campoBusca.value.trim();

        if (requisicaoAtual) {
            requisicaoAtual.abort();
            requisicaoAtual = null;
        }

        if (termo === '') {
            ocultarResultados();
            return;
        }

        temporizadorBusca = window.setTimeout(() => pesquisar(termo), 250);
    }

    campoBusca.addEventListener('input', iniciarPesquisa);
    formularioBusca?.addEventListener('submit', (evento) => {
        evento.preventDefault();
        window.clearTimeout(temporizadorBusca);
        const termo = campoBusca.value.trim();

        if (termo === '') {
            ocultarResultados();
            return;
        }

        pesquisar(termo);
    });
}

