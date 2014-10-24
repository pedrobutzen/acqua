var caminho = "http://localhost/acqua/";
var locale = String(window.location.href);
locale = locale.split("/");
var pagina_url = locale[4];
switch (locale[4]) {
    case 'peca':
        listar('peca', 1, 9);
        function cadastrar_peca() {
            var descricao = $('input[name=descricao]').val();
            var marca = $('input[name=marca]').val();
            var cor = $('input[name=cor]').val();
            var tamanho = $('input[name=tamanho]').val();
            var tipo = $('select[name=tipo]').find(":selected").val();
            if (descricao === "" || marca === "" || cor === "" || tipo === "") {
                alert_open("danger", "Os campos com * são obrigatórios.");
            } else {
                if (tipo === "outro") {
                    tipo = $('input[name=tipo_outro]').val();
                }
                $.ajax({
                    type: 'GET',
                    url: 'action/action.php',
                    dataType: 'json',
                    data: {
                        action_pagina: "peca",
                        action: "cadastrar",
                        descricao: descricao,
                        marca: marca,
                        cor: cor,
                        tamanho: tamanho,
                        idtipo: tipo
                    },
                    success: function (retorno) {
                        if (retorno.erro === false) {
                            listar('peca', 1, 9);
                            alert_open("success", "Cadastrado com sucesso.");
                            $('#cs-form input').val('');
                            $('#cs-form select').val('');
                            $('div.cs-outro-form').hide();
                        } else {
                            alert_open("danger", retorno.msg_erro);
                        }
                    },
                    error: function (retorno) {
                        alert_open("danger", "Erro inesperando, tente novamente mais tarde.");
                    }
                });
            }
        }
        function editar_peca() {
            var idpeca = $('input[name=id-editar]').val();
            var descricao = $('input[name=descricao]').val();
            var marca = $('input[name=marca]').val();
            var cor = $('input[name=cor]').val();
            var tamanho = $('input[name=tamanho]').val();
            var tipo = $('select[name=tipo]').find(":selected").val();
            if (descricao === "" || marca === "" || cor === "" || tipo === "") {
                alert_open("danger", "Os campos com * são obrigatórios.");
            } else {
                if (tipo === "outro") {
                    tipo = $('input[name=tipo_outro]').val();
                }
                $.ajax({
                    type: 'GET',
                    url: 'action/action.php',
                    dataType: 'json',
                    data: {
                        action_pagina: "peca",
                        action: "editar",
                        action_id: idpeca,
                        descricao: descricao,
                        marca: marca,
                        cor: cor,
                        tamanho: tamanho,
                        idtipo: tipo
                    },
                    success: function (retorno) {
                        if (retorno.erro === false) {
                            listar('peca', 1, 9);
                            alert_open("success", "Peça editada com sucesso.");
                            limpar_form_cadastro();
                        } else {
                            alert_open("danger", retorno.msg_erro);
                        }
                    },
                    error: function () {
                        alert_open("danger", "Erro inesperando, tente novamente mais tarde.");
                    }
                });
            }
        }
        break;
    case 'ocorrencia':
        listar('ocorrencia', 1, 9);
        break;
    case 'lancamento':
        $(document).ready(function () {
            $("#pecas-lancamento").multiSelect({
                selectableHeader: "<div>Peças disponíveis para lavagem</div>",
                selectionHeader: "<div>Peças para Lavar - (<span id=\'qtd-pecas\'>15</span> peças restantes)</div>",
                selectableFooter: "<div>Clique na descrição da peça para marcar</div>",
                selectionFooter: "<div>Clique na descrição da peça para remover da lavegem</div>",
                afterSelect: function () {
                    verifica_qtd_marcados();
                },
                afterDeselect: function () {
                    verifica_qtd_marcados();
                }
            });
            verifica_qtd_marcados();
        });
        function cadastrar_lancamento() {
            var selecionadas = $('#pecas-lancamento').val();
            if (selecionadas === null) {
                alert_open("danger", "Nenhuma peça marcada para o lançamento.");
            } else if (selecionadas.length > 15) {
                var str_alert = "";
                if ((selecionadas.length - 15) > 1) {
                    str_alert = "Você marcou " + selecionadas.length + " peças, o limite é de 15 peças, desmarque " + (selecionadas.length - 15) + " peças para continuar.";
                } else {
                    str_alert = "Você marcou " + selecionadas.length + " peças, o limite é de 15 peças, desmarque " + (selecionadas.length - 15) + " peça para continuar.";
                }
                alert_open("danger", str_alert);
            } else {
                alert_close("all");
                $.ajax({
                    type: 'GET',
                    url: 'action/action.php',
                    dataType: 'json', data: {
                        action_pagina: "lancamento",
                        action: "cadastrar",
                        selecionadas: selecionadas
                    },
                    success: function (retorno) {
                        if (retorno.erro === false) {
                            alert_open("success", "Lançamento efetuado com sucesso.");
                        } else if (retorno.erro === true) {
                            alert_open("danger", retorno.msg_erro);
                        } else {
                            alert_open("success", retorno.msg_success);
                        }
                    },
                    error: function () {
                        alert_open("danger", "Erro inesperando, tente novamente mais tarde.");
                    }
                });
            }
        }
        function verifica_qtd_marcados() {
            var marcados = $('#pecas-lancamento').val();
            if (typeof marcados !== "undefined") {
                if (marcados !== null) {
                    var restante = 15 - marcados.length;
                    $('#qtd-pecas').html(restante);
                    if (marcados.length > 15) {
                        var str_alert = "";
                        if ((marcados.length - 15) > 1) {
                            str_alert = "Você marcou " + marcados.length + " peças, o limite é de 15 peças, desmarque " + (marcados.length - 15) + " peças para continuar.";
                        } else {
                            str_alert = "Você marcou " + marcados.length + " peças, o limite é de 15 peças, desmarque " + (marcados.length - 15) + " peça para continuar.";
                        }
                        alert_open("danger", str_alert);
                    } else {
                        alert_close("all");
                    }
                } else {
                    alert_close("all");
                    $('#qtd-pecas').html("15");
                }
            }
        }
        break;
    case 'lancamentoativo':
        listar('lancamentoativo', 1, 9);
        break;
    case 'lancamentospassados':
        listar('lancamentospassados', 1, 9);
        break;
    case 'usuario':
        listar('usuario-funcionario', 1, 9);
        function cadastrar_usuario_funcionario() {
            var nome_usuario = $('input[name=usuario_nome]').val();
            var usuario_usuario = $('input[name=usuario_usuario]').val();
            var ramal_usuario = $('input[name=usuario_telefone]').val();
            var sexo_usuario = $('select[name=usuario_sexo]').find(":selected").val();
            var permissao_usuario = $('select[name=usuario_permissao]').find(":selected").val();
            if (nome_usuario === "" || usuario_usuario === "" || permissao_usuario === "" || sexo_usuario === "") {
                alert_open("danger", "Os campos com * são obrigatórios.");
            } else {
                $.ajax({
                    type: 'GET',
                    url: 'action/action.php',
                    dataType: 'json', data: {
                        action_pagina: "usuario-funcionario",
                        action: "cadastrar",
                        nome_usuario_cadastrar: nome_usuario,
                        usuario_usuario_cadastrar: usuario_usuario,
                        sexo_usuario: sexo_usuario,
                        ramal_usuario_cadastrar: ramal_usuario,
                        permissao_usuario_cadastrar: permissao_usuario
                    },
                    success: function (retorno) {
                        if (retorno.erro === false) {
                            listar('usuario-funcionario', 1, 9);
                            alert_open("success", "Cadastrado com sucesso.");
                            $('#cs-form input').val('');
                        } else {
                            alert_open("danger", retorno.msg_erro);
                        }
                    },
                    error: function (retorno) {
                        alert_open("danger", "Erro inesperando, tente novamente mais tarde.");
                    }
                });
            }
        }
        function editar_usuario_funcionario() {
            var usuario_usuario_editar = $('input[name=id-editar]').val();
            var nome_usuario = $('input[name=usuario_nome]').val();
            var usuario_usuario = $('input[name=usuario_usuario]').val();
            var ramal_usuario = $('input[name=usuario_telefone]').val();
            var sexo_usuario = $('select[name=usuario_sexo]').find(":selected").val();
            var permissao_usuario = $('select[name=usuario_permissao]').find(":selected").val();
            if (nome_usuario === "" || usuario_usuario === "" || permissao_usuario === "" || sexo_usuario === "") {
                alert_open("danger", "Os campos com * são obrigatórios.");
            } else {
                $.ajax({
                    type: 'GET',
                    url: 'action/action.php',
                    dataType: 'json',
                    data: {
                        action_pagina: "usuario-funcionario",
                        action: "editar",
                        action_id: usuario_usuario_editar, nome_usuario: nome_usuario, usuario_usuario: usuario_usuario,
                        sexo_usuario: sexo_usuario,
                        ramal_usuario: ramal_usuario,
                        permissao_usuario: permissao_usuario
                    },
                    success: function (retorno) {
                        if (retorno.erro === false) {
                            listar('usuario-funcionario', 1, 9);
                            alert_open("success", "Usuário editado com sucesso.");
                            limpar_form_cadastro();
                        } else {
                            alert_open("danger", retorno.msg_erro);
                        }
                    },
                    error: function (retorno) {
                        alert_open("danger", retorno.msg_erro);
                    }
                });
            }
        }
        break;
    case 'numero':
        listar('numero_feminino', 1, 9);
        function cadastrar_numero() {
            var num_numero = $('input[name=num_numero]').val();
            var num_sexo = $('select[name=num_sexo]').find(":selected").val();
            if (num_numero === "" || num_sexo === "") {
                alert_open("danger", "Todos campos são obrigatórios.");
            } else {
                $.ajax({
                    type: 'GET',
                    url: 'action/action.php',
                    dataType: 'json',
                    data: {
                        action_pagina: "numero",
                        action: "cadastrar",
                        num_numero: num_numero,
                        num_sexo: num_sexo
                    },
                    success: function (retorno) {
                        if (retorno.erro === false) {
                            if (num_sexo === "f") {
                                if (!$('.cs-li-feminino').hasClass('active')) {
                                    $('.cs-li-masculino').removeClass('active');
                                    $('.cs-li-feminino').addClass('active');
                                }
                                listar('numero_feminino', 1, 9);
                            } else {
                                if (!$('.cs-li-masculino').hasClass('active')) {
                                    $('.cs-li-feminino').removeClass('active');
                                    $('.cs-li-masculino').addClass('active');
                                }
                                listar('numero_masculino', 1, 9);
                            }
                            alert_open("success", "Cadastrado com sucesso.");
                            $('#cs-form input').val('');
                            $('#cs-form select').val('');
                        } else {
                            alert_open("danger", retorno.msg_erro);
                        }
                    },
                    error: function () {
                        alert_open("danger", "Erro inesperando, tente novamente mais tarde.");
                    }
                });
            }
        }
        break;
    case 'atribuirnumero':
        listar('numero_feminino', 1, 9);
        function atribuir_numero_auto() {
            var sexo = $('select[name=num_sexo]').find(":selected").val();
            if (sexo === "") {
                alert_open("danger", "Todos campos são obrigatórios.");
            } else {
                $.ajax({
                    type: 'GET',
                    url: 'action/action.php',
                    dataType: 'json',
                    data: {
                        action_pagina: "numero",
                        action: "atribuir_numero_auto",
                        sexo: sexo
                    },
                    success: function (retorno) {
                        if (retorno.erro === false) {
                            alert_open("success", "Atribuido com sucesso.");
                            $('#cs-form select').val('');
                        } else {
                            alert_open("danger", retorno.msg_erro);
                        }
                        if (sexo === "f") {
                            if (!$('.cs-li-feminino').hasClass('active')) {
                                $('.cs-li-masculino').removeClass('active');
                                $('.cs-li-feminino').addClass('active');
                            }
                            listar('numero_feminino', 1, 9);
                        } else {
                            if (!$('.cs-li-masculino').hasClass('active')) {
                                $('.cs-li-feminino').removeClass('active');
                                $('.cs-li-masculino').addClass('active');
                            }
                            listar('numero_masculino', 1, 9);
                        }
                    },
                    error: function () {
                        alert_open("danger", "Erro inesperando, tente novamente mais tarde.");
                        if (sexo === "f") {
                            if (!$('.cs-li-feminino').hasClass('active')) {
                                $('.cs-li-masculino').removeClass('active');
                                $('.cs-li-feminino').addClass('active');
                            }
                            listar('numero_feminino', 1, 9);
                        } else {
                            if (!$('.cs-li-masculino').hasClass('active')) {
                                $('.cs-li-feminino').removeClass('active');
                                $('.cs-li-masculino').addClass('active');
                            }
                            listar('numero_masculino', 1, 9);
                        }
                    }
                });
            }
        }
        break;
    default:
        break;
}
// ****************** IDIOMA

// ****************** LIVRO 
function cadastrar_livro() {
    var titulo = $('input[name=titulo]').val();
    var sigla = $('input[name=sigla]').val();
    //var capa = $("input[name=img_capa]").val();
    //var id_idioma = $("input[name=id_idioma_livro]").val();

    if (titulo === "" || sigla === "") {
        $('div#cs-alert-success').hide();
        $('span#cs-message-danger').text("Todos campos são obrigatórios.");
        $('div#cs-alert-danger').show();
        return false;
    } else {
        $.ajax({
            type: 'GET',
            url: 'action/action.php',
            dataType: 'json',
            data: {
                action_pagina: "livro",
                action: "cadastrar",
                titulo_livro_cadastrar: titulo,
                sigla_livro_cadastrar: sigla,
                capa_livro_cadastrar: capa,
                id_idioma_livro_cadastrar: id_idioma
            },
            success: function (retorno) {
                if (retorno.erro === false) {
                    listar('livro', 1, 9);
                    $('span#cs-message-success').text("Cadastrado com sucesso.");
                    $('div#cs-alert-danger').hide();
                    $('div#cs-alert-success').show();
                    $('#cs-form input').val('');
                } else {
                    $('div#cs-alert-success').hide();
                    $('span#cs-message-danger').text(retorno.msg_erro);
                    $('div#cs-alert-danger').show();
                }
            },
            error: function () {
                alert_open("danger", "Erro inesperando, tente novamente mais tarde.");
            }
        });
    }
}
function editar_livro() {
    var titulo = $('input[name=titulo]').val();
    var sigla = $('input[name=sigla]').val();
    if (titulo === "" || sigla === "") {
        alert_open("danger", "Os campos com * são obrigatórios.");
    } else {
        $.ajax({
            type: 'GET',
            url: 'action/action.php',
            dataType: 'json',
            data: {
                action_pagina: "livro",
                action: "editar",
                id_livro_editar: $('input[name=id_livro]').val(),
                titulo_livro_editar: titulo,
                sigla_livro_editar: sigla
            },
            success: function (retorno) {
                if (retorno.erro === false) {
                    listar('livro', 1, 9);
                    $('span#cs-message-success').text("Editado com sucesso.");
                    $('div#cs-alert-danger').hide();
                    $('div#cs-alert-success').show();
                    limpar_form_cadastro();
                } else {
                    $('div#cs-alert-success').hide();
                    $('span#cs-message-danger').text(retorno.msg_erro);
                    $('div#cs-alert-danger').show();
                }
            },
            error: function () {
                alert_open("danger", "Erro inesperando, tente novamente mais tarde.");
            }
        });
    }
}

function log(texto) {
    console.log(texto);
}

// ------- USUÁRIO -------
function logar() {
    var usuario_logar = $('input[name=usuario]').val();
    var senha_usuario_logar = $('input[name=senha]').val();
    if (usuario_logar === "" && senha_usuario_logar === "") {
        alert_open("danger", "Todos os campos são obrigatórios.");
    } else if (usuario_logar === "") {
        alert_open("danger", "Digite o usuário.");
    } else if (senha_usuario_logar === "") {
        alert_open("danger", "Digite a senha.");
    } else {
        $.ajax({
            type: 'GET',
            url: 'action/action.php',
            dataType: 'json',
            data: {
                action_pagina: 'usuario',
                action: 'logar',
                usuario_logar_usuario: usuario_logar,
                usuario_logar_senha: senha_usuario_logar
            },
            success: function (retorno) {
                if (retorno.erro === false) {
                    location.href = caminho;
                } else {
                    alert_open("danger", retorno.msg_erro);
                }
            },
            error: function () {
                alert_open("danger", "Erro inesperando, tente novamente mais tarde.");
            }
        });
    }
}
function deslogar() {
    $.ajax({
        type: 'GET',
        url: 'action/action.php',
        dataType: 'json',
        data: {
            action_pagina: 'usuario',
            action: 'deslogar'}, success: function (retorno) {
            if (retorno.erro === false) {
                location.href = caminho + "login";
            } else {
                alert_open("danger", retorno.msg_erro);
            }
        },
        error: function () {
            alert_open("danger", "Erro inesperando, tente novamente mais tarde.");
        }
    });
}

// ------- GERAL -------
function listar(action_pagina, pagina_paginacao, qtd_itens) {
    limpar_form_cadastro();
    $('div#cs-loading').fadeIn(100);
    $.ajax({
        type: 'GET',
        url: 'action/action.php',
        dataType: 'json',
        data: {
            action_pagina: action_pagina,
            action: "listar",
            listar_pag: pagina_paginacao,
            listar_qtd_itens: qtd_itens
        },
        success: function (retorno) {
            var html_tags = "";
            if (retorno.erro === false) {
                var a = 0;
                html_tags = "";
                $.each(retorno, function () {
                    a++;
                });
                switch (action_pagina) {
                    case "peca":
                        $('div.cs-legenda').html('');
                        for (var j = 1; j < a - 1; j++) {
                            if (retorno[j].idocorrencia !== "") {
                                $('div.cs-legenda').html('<span class="gray">*Peça com ocorrência ativa registrada</span>');
                                html_tags += '<tr class="gray" data-id="' + retorno[j].idpeca + '" data-pagina="peca" title="Clique para detalhar peça"><td>' + retorno[j].descricao + '</td><td>' + retorno[j].nometipo + '</td><td>' + retorno[j].marca + '</td><td>' + retorno[j].cor + '</td><td>' + retorno[j].tamanho + '</td><td>' + retorno[j].tipoocorrencia + '</td></tr>';
                            } else {
                                html_tags += '<tr data-id="' + retorno[j].idpeca + '" data-pagina="peca" title="Clique para detalhar peça"><td>' + retorno[j].descricao + '</td><td>' + retorno[j].nometipo + '</td><td>' + retorno[j].marca + '</td><td>' + retorno[j].cor + '</td><td>' + retorno[j].tamanho + '</td><td>Normal</td></tr>';
                            }
                        }
                        break;
                    case "ocorrencia":
                        $('div.cs-legenda').html('');
                        for (var j = 1; j < a - 1; j++) {
                            if (retorno[j].ocorrenciastatus === "1") {
                                $('div.cs-legenda').html('<span class="gray">*Peça com ocorrência ativa registrada</span>');
                                html_tags += '<tr class="gray" data-id="' + retorno[j].idpeca + '" data-pagina="ocorrencia" title="Clique para detalhar peça"><td>' + retorno[j].descricao + '</td><td>' + retorno[j].nometipo + '</td><td>' + retorno[j].marca + '</td><td>' + retorno[j].cor + '</td><td>' + retorno[j].ocorrenciadescricao + '</td><td>' + retorno[j].tipoocorrencia + '</td></tr>';
                            } else {
                                html_tags += '<tr data-id="' + retorno[j].idpeca + '" data-pagina="ocorrencia" title="Clique para detalhar peça"><td>' + retorno[j].descricao + '</td><td>' + retorno[j].nometipo + '</td><td>' + retorno[j].marca + '</td><td>' + retorno[j].cor + '</td><td>' + retorno[j].ocorrenciadescricao + '</td><td>' + retorno[j].tipoocorrencia + '</td></tr>';
                            }
                        }
                        break;
                    case "lancamentoativo":
                        for (var j = 1; j < a - 1; j++) {
                            html_tags += '<tr><td>' + retorno[j].descricao + '</td><td>' + retorno[j].nometipo + '</td><td>' + retorno[j].marca + '</td><td>' + retorno[j].cor + '</td><td>' + retorno[j].tamanho + '</td></tr>';
                        }
                        break;
                    case "lancamentospassados":
                        for (var j = 1; j < a - 1; j++) {
                            html_tags += '<tr data-id="' + retorno[j].idlancamento + '" data-pagina="lancamentospassados" title="Clique para visualizar peças do lançamento"><td>' + retorno[j].data_criacao + '</td><td>' + retorno[j].data_recebimento + '</td><td>' + retorno[j].usuario_recebimento + '</td><td>' + retorno[j].data_devolucao + '</td><td>' + retorno[j].usuario_devolucao + '</td></tr>';
                        }
                        break;
                    case "usuario-funcionario":
                        $('#cs-thead-dataGrid').html('<tr><th>Nome</th><th>Usuário</th><th>Telefone/Contato</th></tr>');
                        for (var j = 1; j < a - 1; j++) {
                            var class_tr = '';
                            switch (retorno[j].permissao) {
                                case '0':
                                    class_tr = 'danger';
                                    break;
                                case '1':
                                    class_tr = 'warning';
                                    break;
                                case '2':
                                    class_tr = 'success';
                                    break;
                                default:
                                    break;
                            }
                            html_tags += '<tr class="' + class_tr + '" data-id="' + retorno[j].usuario + '" data-pagina="usuario-funcionario" title="Clique para detalhar usuário"><td>' + retorno[j].nome + '</td><td>' + retorno[j].usuario + '</td><td>' + retorno[j].ramal + '</td></tr>';
                        }
                        $('div.cs-legenda').html('<span class="label-danger">Total</span> - <span class="label-warning">Parcial</span> - <span class="label-success">Visualização Relatórios/Gráficos</span>');
                        break;
                    case "usuario-aluno":
                        $('#cs-thead-dataGrid').html('<tr><th>Nome</th><th>Usuário</th><th>Ramal</th><th>Quarto</th></tr>');
                        $('div.cs-legenda').html('');
                        $('div.cs-legenda').html('<span class="label-danger">Aluno bloqueado</span> <span class="gray">Aluno sem número</span>');
                        for (var j = 1; j < a - 1; j++) {
                            if (retorno[j].bloqueado === "" || retorno[j].bloqueado === "0") {
                                if (retorno[j].num === "") {
                                    html_tags += '<tr class="gray" data-id="' + retorno[j].usuario + '" data-pagina="usuario-aluno" title="Clique para detalhar usuário"><td>' + retorno[j].nome + '</td><td>' + retorno[j].usuario + '</td><td>' + retorno[j].ramal + '</td><td>' + retorno[j].quarto + '</td></tr>';
                                } else {
                                    html_tags += '<tr data-id="' + retorno[j].usuario + '" data-pagina="usuario-aluno" title="Clique para detalhar usuário"><td>' + retorno[j].nome + '</td><td>' + retorno[j].usuario + '</td><td>' + retorno[j].ramal + '</td><td>' + retorno[j].quarto + '</td></tr>';
                                }
                            } else {
                                html_tags += '<tr class="danger" data-id="' + retorno[j].usuario + '" data-pagina="usuario-aluno" title="Clique para detalhar usuário"><td>' + retorno[j].nome + '</td><td>' + retorno[j].usuario + '</td><td>' + retorno[j].ramal + '</td><td>' + retorno[j].quarto + '</td></tr>';
                            }

                        }
                        break;
                    case "numero_feminino":
                        $('div.cs-legenda').html('');
                        $('div.cs-legenda').html('<span class="label-success">Número disponível</span>');
                        for (var j = 1; j < a - 1; j++) {
                            if (retorno[j].nome_usuario === "") {
                                html_tags += '<tr class="success"><td>' + retorno[j].num + '</td><td>Disponível</td></tr>';
                            } else {
                                html_tags += '<tr><td>' + retorno[j].num + '</td><td>' + retorno[j].nome_usuario + '</td></tr>';
                            }
                        }
                        break;
                    case "numero_masculino":
                        $('div.cs-legenda').html('');
                        $('div.cs-legenda').html('<span class="label-success">Número disponível</span>');
                        for (var j = 1; j < a - 1; j++) {
                            if (retorno[j].nome_usuario === "") {
                                html_tags += '<tr class="success"><td>' + retorno[j].num + '</td><td>Disponível</td></tr>';
                            } else {
                                html_tags += '<tr><td>' + retorno[j].num + '</td><td>' + retorno[j].nome_usuario + '</td></tr>';
                            }
                        }
                        break;
                    default:
                        break;
                }
                $('button.cs-editar').click(function () {
                    switch ($(this).attr('data-action')) {
                        case "editar_peca":
                            editar_peca();
                            break;
                        case "editar_usuario":
                            editar_usuario_funcionario();
                            break;
                        default :
                            break;
                    }
                });
                $('#cs-dataGrid').html(html_tags);
                paginar(action_pagina, pagina_paginacao, qtd_itens, retorno[0].qtd_geral);
                $('ul#cs-pagination a').click(function () {
                    listar($(this).attr('data-pagina'), $(this).attr('data-paginacao'), $(this).attr('data-qtd-itens'));
                });
                $('.cs-with-modal tr').click(function () {
                    var titulo_modal;
                    switch ($(this).attr('data-pagina')) {
                        case 'peca':
                            titulo_modal = 'Detalhes de Peça';
                            break;
                        case 'ocorrencia':
                            titulo_modal = 'Detalhes de Peça com Ocorrência';
                            break;
                        case 'lancamentospassados':
                            titulo_modal = 'Detalhes do Lançamento';
                            break;
                        case 'usuario-aluno':
                            titulo_modal = 'Detalhes do Aluno';
                            break;
                        case 'usuario-funcionario':
                            titulo_modal = 'Detalhes do Funcionário';
                            break;
                        default :
                            titulo_modal = 'Página não identificada';
                            break;
                    }
                    modal_open($(this).attr('data-id'), $(this).attr('data-pagina'), titulo_modal);
                    $('ul.dropdown-menu a#pre_editar').click(function () {
                        pre_editar($(this).attr('data-pagina'), $(this).attr('data-id'));
                    });
                });
            } else {
                alert_open("danger", retorno.msg_erro);
            }
        },
        error: function () {
            alert_open("danger", "Impossível listar " + action_pagina);
        }
    });
}
function limpar_form_cadastro() {
    $('input').val('');
    $('select').val('');
    $('button.cs-cancelar').hide();
    $('button.cs-editar').hide();
    $('button.cs-salvar').show();
    $('panel-body.cs-id-editar span').html('');
    $('span#cs-action').text("Cadastrar");
}
function pre_editar(pagina, id) {
    $('div#cs-alert-danger').hide();
    $('div#cs-alert-success').hide();
    $('button.cs-pre-cadastrar').show();
    $.ajax({type: 'GET',
        url: 'action/action.php',
        dataType: 'json',
        data: {
            action_pagina: pagina,
            action: "montar",
            action_id: id
        },
        success: function (retorno) {
            $('span#cs-action').text("Editar");
            $('button.cs-salvar').hide();
            $('button.cs-editar').show();
            $('button.cs-cancelar').show();
            if (retorno.erro === false) {
                switch (pagina) {
                    case 'usuario-funcionario':
                        $('div.cs-id-editar').html('<input type="text" name="id-editar" class="form-control" style="display:none;">');
                        $('input[name=usuario_nome]').val(retorno[0].nome);
                        $('input[name=id-editar]').val(retorno[0].usuario);
                        $('input[name=usuario_usuario]').val(retorno[0].usuario);
                        $('input[name=usuario_telefone]').val(retorno[0].ramal);
                        $('select[name=usuario_sexo]').val(retorno[0].sexo);
                        $('select[name=usuario_permissao]').val(retorno[0].permissao);
                        break;
                    case 'peca':
                        $('div.cs-id-editar').html('<input type="text" name="id-editar" class="form-control" style="display:none;">');
                        $('input[name=descricao]').val(retorno[0].descricaopeca);
                        $('input[name=id-editar]').val(retorno[0].idpeca);
                        $('input[name=marca]').val(retorno[0].marca);
                        $('input[name=cor]').val(retorno[0].cor);
                        $('input[name=tamanho]').val(retorno[0].tamanho);
                        $('select[name=tipo]').val(retorno[0].idtipo);
                        break;
                    default:
                        break;
                }
            } else {
                alert_open('danger', retorno.msg_erro);
            }
        },
        error: function () {
            alert_open("danger", "Erro inesperando, tente novamente mais tarde.");
        }
    });
}
function excluir(pagina, id) {
    $.ajax({
        type: 'GET',
        url: 'action/action.php',
        dataType: 'json',
        data: {
            action_pagina: pagina,
            action: "excluir",
            action_id: id
        },
        success: function (retorno) {
            listar(pagina, 1, 9);
            if (retorno.erro === "success") {
                alert_open("success", retorno.msg_status);
            } else if (retorno.erro === false) {
                alert_open("success", "Excluido com sucesso.");
            } else {
                alert_open("danger", retorno.msg_erro);
            }
        },
        error: function () {
            alert_open("danger", "Erro inesperando, tente novamente mais tarde.");
        }
    });
}
function paginar(action_pagina, pagina_paginacao, qtd_itens_por_pagina, qtd_geral) {
    pagina_paginacao = parseInt(pagina_paginacao);
    if (qtd_geral > qtd_itens_por_pagina) {
        $('#cs-pagination-content').html('<ul class="pagination" id="cs-pagination"></ul>');
        if (pagina_paginacao !== 1) {
            $('#cs-pagination').append('<li><a href="javascript:;" data-pagina="' + action_pagina + '" data-paginacao="' + (pagina_paginacao - 1) + '" data-qtd-itens="' + qtd_itens_por_pagina + '">«</a></li>');
        }
        var qtdpaginas = Math.ceil(qtd_geral / qtd_itens_por_pagina);
        for (var i = 1; i <= qtdpaginas; i++) {
            if (pagina_paginacao === i) {
                $('#cs-pagination').append('<li class="active"><a href="javascript:;" data-pagina="' + action_pagina + '" data-paginacao="' + i + '" data-qtd-itens="' + qtd_itens_por_pagina + '">' + i + '</a></li>');
            } else {
                $('#cs-pagination').append('<li><a href="javascript:;" data-pagina="' + action_pagina + '" data-paginacao="' + i + '" data-qtd-itens="' + qtd_itens_por_pagina + '">' + i + '</a></li>');
            }
        }
        if (pagina_paginacao !== qtdpaginas) {
            $('#cs-pagination').append('<li><a href="javascript:;" data-pagina="' + action_pagina + '" data-paginacao="' + (pagina_paginacao + 1) + '" data-qtd-itens="' + qtd_itens_por_pagina + '">»</a></li>');
        }
    } else {
        $('#cs-pagination-content').html('');
    }
}
function alert_open(tipo, mensagem) {
    alert_close("all");
    if (tipo === "danger") {
        $('div#cs-alert-danger span').text(mensagem);
        $('div#cs-alert-danger').fadeIn();
    } else if (tipo === "success") {
        $('div#cs-alert-success span').text(mensagem);
        $('div#cs-alert-success').fadeIn();
    }
    setTimeout(function () {
        alert_close("all");
    }, 10000);
}
function alert_close(tipo) {
    if (tipo === "danger") {
        $('div#cs-alert-danger').hide();
    } else if (tipo === "success") {
        $('div#cs-alert-success').hide();
    } else if (tipo === "all") {
        $('div#cs-alert-danger').hide();
        $('div#cs-alert-success').hide();
    }
}
function modal_open(id, pagina, titulo) {
    $.ajax({
        type: 'GET',
        url: 'action/action.php',
        dataType: 'json',
        data: {
            action_pagina: pagina,
            action: "montar",
            action_id: id
        },
        success: function (retorno) {
            if (retorno.erro === false) {
                var html_body, html_footer;

                var btn_voltar = '<button type="button" class="btn btn-default cs-voltar">Voltar</button>';
                var btn_editar = '<button type="button" data-id="' + id + '" data-pagina="' + pagina + '" class="btn btn-primary cs-editar">Editar</button>';
                var btn_excluir = '<button type="button" data-id="' + id + '" data-pagina="' + pagina + '" class="btn btn-primary cs-excluir-info">Excluir</button>';

                if (pagina === "usuario-aluno" || pagina === "usuario-funcionario") {
                    var permissao_nome;
                    var btn_reset_senha = '<button type="button" data-id="' + id + '" class="btn btn-primary cs-reset-senha-info">Reiniciar Senha</button>';

                    var btn_alterar_num = '<button type="button" data-id="' + id + '" data-pagina="usuario-aluno" class="btn btn-primary cs-conf-alterar-num">Alterar Num</button>';
                    var btn_atribuir_num = '<button type="button" data-id="' + id + '" data-pagina="usuario-aluno" class="btn btn-primary cs-conf-alterar-num">Atribuir Num</button>';

                    var btn_desbloquear = '<button type="button" data-id="' + id + '" class="btn btn-primary cs-desbloquear">Desbloquear</button>';
                    var btn_bloquear = '<button type="button" data-id="' + id + '" class="btn btn-primary cs-bloquear">Bloquear</button>';
                    switch (retorno[0].permissao) {
                        case '0':
                            permissao_nome = 'Total';
                            break;
                        case '1':
                            permissao_nome = 'Parcial';
                            break;
                        case '2':
                            permissao_nome = 'Visualização Relatórios/Gráficos';
                            break;
                        default :
                            permissao_nome = 'Permissão não identificada';
                            break;
                    }
                    var sexo = Array();
                    sexo["f"] = "Feminino";
                    sexo["m"] = "Masculino";
                    switch (pagina) {
                        case 'usuario-funcionario':
                            html_body = '<ul class="list-unstyled" id="cs-list-modal">';
                            html_body += '<li><strong>Nome:</strong>' + retorno[0].nome + '</li>';
                            html_body += '<li><strong>Usuário:</strong>' + retorno[0].usuario + '</li>';
                            html_body += '<li><strong>Sexo:</strong>' + sexo[retorno[0].sexo] + '</li>';
                            html_body += '<li><strong>Telefone/Contato:</strong>' + retorno[0].ramal + '</li>';
                            html_body += '<li><strong>Permissão:</strong>' + permissao_nome + '</li>';
                            html_body += '</ul>';
                            html_footer = btn_reset_senha + btn_editar + btn_excluir;
                            break;
                        case 'usuario-aluno':
                            html_body = '<ul class="list-unstyled" id="cs-list-modal">';
                            html_body += '<li><strong>Nome:</strong>' + retorno[0].nome + '</li>';
                            html_body += '<li><strong>Usuário:</strong>' + retorno[0].usuario + '</li>';
                            html_body += '<li><strong>Sexo:</strong>' + sexo[retorno[0].sexo] + '</li>';
                            if (retorno[0].num === "") {
                                html_body += '<li class="gray"><strong>Número:</strong><span id="cs-num-aluno">Aluno sem número</span></li>';
                            } else {
                                html_body += '<li><strong>Número:</strong><span id="cs-num-aluno">' + retorno[0].num + '</span></li>';
                            }
                            html_body += '<li><strong>Ramal:</strong>' + retorno[0].ramal + '</li>';
                            html_body += '<li><strong>Quarto:</strong>' + retorno[0].quarto + '</li>';
                            if (retorno[0].bloqueado === "1") {
                                html_body += '<li><strong>Status:</strong>Bloqueado</li>';
                            } else {
                                html_body += '<li><strong>Status:</strong>Desbloqueado</li>';
                            }
                            html_body += '</ul>';
                            html_footer = btn_reset_senha;
                            if (retorno[0].num === "") {
                                html_footer += btn_atribuir_num;
                            } else {
                                html_footer += btn_alterar_num;
                            }
                            if (retorno[0].bloqueado === "" || retorno[0].bloqueado === "0") {
                                html_footer += btn_bloquear;
                            } else {
                                html_footer += btn_desbloquear;
                            }

                            break;
                        default:
                            break;
                    }
                } else if (pagina === "peca") {
                    html_body = '<ul class="list-unstyled" id="cs-list-modal">';
                    html_body += '<li><strong>Descrição:</strong>' + retorno[0].descricaopeca + '</li>';
                    html_body += '<li><strong>Marca:</strong>' + retorno[0].marca + '</li>';
                    html_body += '<li><strong>Cor:</strong>' + retorno[0].cor + '</li>';
                    html_body += '<li><strong>Tamanho:</strong>' + retorno[0].tamanho + '</li>';
                    html_body += '<li><strong>Tipo:</strong>' + retorno[0].nometipo + '</li>';
                    html_body += '</ul>';
                    if (retorno[0].ocorrencia.qtd_ocorrencias > 0) {
                        var status_ocorrencia = Array();
                        status_ocorrencia["0"] = "Finalizada";
                        status_ocorrencia["1"] = "Ativa";
                        html_body += '<br><div class="table-responsive"><div class="text-center">Ocorrências</div><table class="table table-condensed"><thead><tr><th>Descrição</th><th>Tipo</th><th>Status</th></tr></thead><tbody>';
                        for (var i = 0; i < retorno[0].ocorrencia.qtd_ocorrencias; i++) {
                            html_body += '<tr><td>' + retorno[0].ocorrencia[i].descricao + '</td><td>' + retorno[0].ocorrencia[i].tipoocorrenca + '</td><td>' + status_ocorrencia[retorno[0].ocorrencia[i].status] + '</td></tr>';
                        }
                        html_body += '</tbody></table></div>';
                    }
                    html_footer = btn_editar + btn_excluir;
                } else if (pagina === "ocorrencia") {
                    html_body = '<ul class="list-unstyled" id="cs-list-modal">';
                    html_body += '<li><strong>Descrição:</strong>' + retorno[0].descricaopeca + '</li>';
                    html_body += '<li><strong>Marca:</strong>' + retorno[0].marca + '</li>';
                    html_body += '<li><strong>Cor:</strong>' + retorno[0].cor + '</li>';
                    html_body += '<li><strong>Tamanho:</strong>' + retorno[0].tamanho + '</li>';
                    html_body += '<li><strong>Tipo:</strong>' + retorno[0].nometipo + '</li>';
                    html_body += '</ul>';
                    if (retorno[0].ocorrencia.qtd_ocorrencias > 0) {
                        var status_ocorrencia = Array();
                        status_ocorrencia["0"] = "Finalizada";
                        status_ocorrencia["1"] = "Ativa";
                        html_body += '<br><div class="table-responsive"><div class="text-center">Ocorrências</div><table class="table table-condensed"><thead><tr><th>Descrição</th><th>Tipo</th><th>Status</th></tr></thead><tbody>';
                        for (var i = 0; i < retorno[0].ocorrencia.qtd_ocorrencias; i++) {
                            html_body += '<tr><td>' + retorno[0].ocorrencia[i].descricao + '</td><td>' + retorno[0].ocorrencia[i].tipoocorrenca + '</td><td>' + status_ocorrencia[retorno[0].ocorrencia[i].status] + '</td></tr>';
                        }
                        html_body += '</tbody></table></div>';
                    }
                    html_footer = "";
                } else if (pagina === "lancamentospassados") {
                    html_body = '<ul class="list-unstyled" id="cs-list-modal">';
                    html_body += '<li><strong>Data Criação:</strong>' + retorno[0].data_criacao + '</li>';
                    html_body += '<li><strong>Data Recebimento:</strong>' + retorno[0].data_recebimento + '</li>';
                    html_body += '<li><strong>Usuário que Recebeu:</strong>' + retorno[0].usuario_recebimento + '</li>';
                    html_body += '<li><strong>Data Devolução:</strong>' + retorno[0].data_devolucao + '</li>';
                    html_body += '<li><strong>Usuário que Devolveu:</strong>' + retorno[0].usuario_devolucao + '</li>';
                    html_body += '</ul>';
                    if (retorno[0].pecas[0].qtd_pecas > 0) {
                        html_body += '<br><div class="table-responsive"><div class="text-center">Peças</div><table class="table table-condensed"><thead><tr><th>Descrição</th><th>Marca</th><th>Cor</th><th>Tamanho</th><th>Tipo</th></tr></thead><tbody>';
                        for (var i = 1; i <= retorno[0].pecas[0].qtd_pecas; i++) {
                            html_body += '<tr><td>' + retorno[0].pecas[i].descricao + '</td><td>' + retorno[0].pecas[i].marca + '</td><td>' + retorno[0].pecas[i].cor + '</td><td>' + retorno[0].pecas[i].tamanho + '</td><td>' + retorno[0].pecas[i].nometipo + '</td></tr>';
                        }
                        html_body += '</tbody></table></div>';
                    }
                    html_footer = "";
                } else if (pagina === "usuarioentradapeca" || pagina === "usuariosaidapeca") {
                    html_footer = "";
                    var btn_receber_peca = '<button type="button" data-id="' + id + '" class="btn btn-primary cs-receber-peca">Receber Peça</button>';

                    var btn_alterar_num = '<button type="button" data-id="' + id + '" class="btn btn-primary cs-conf-alterar-num">Alterar Num</button>';
                    var btn_atribuir_num = '<button type="button" data-id="' + id + '" class="btn btn-primary cs-conf-alterar-num">Atribuir Num</button>';

                    var btn_bloquear = '<button type="button" data-id="' + id + '" class="btn btn-primary cs-bloquear">Bloquear</button>';
                    var btn_desbloquear = '<button type="button" data-id="' + id + '" class="btn btn-primary cs-desbloquear">Desbloquear</button>';
                    var sexo = Array();
                    sexo["f"] = "Feminino";
                    sexo["m"] = "Masculino";
                    switch (pagina) {
                        case 'usuarioentradapeca':
                            html_body = '<ul class="list-unstyled" id="cs-list-modal">';
                            html_body += '<li><strong>Nome:</strong>' + retorno[0].nome + '</li>';
                            html_body += '<li><strong>Usuário:</strong>' + retorno[0].usuario + '</li>';
                            html_body += '<li><strong>Sexo:</strong>' + sexo[retorno[0].sexo] + '</li>';
                            if (retorno[0].num === "") {
                                html_body += '<li class="gray"><strong>Número:</strong><span id="cs-num-aluno">Aluno sem número</span></li>';
                            } else {
                                html_body += '<li><strong>Número:</strong><span id="cs-num-aluno">' + retorno[0].num + '</span></li>';
                            }
                            html_body += '<li><strong>Ramal:</strong>' + retorno[0].ramal + '</li>';
                            html_body += '<li><strong>Quarto:</strong>' + retorno[0].quarto + '</li>';
                            if (retorno[0].bloqueado === "1") {
                                html_body += '<li><strong>Status:</strong>Bloqueado</li>';
                            } else {
                                html_body += '<li><strong>Status:</strong>Desbloqueado</li>';
                            }
                            log(retorno[0].lancamentoativo);
                            if (retorno[0].lancamentoativo === 0) {
                                html_body += '<li class="gray"><strong>Status do Lançamento:</strong>Aluno não criou um lançamento</li>';
                            } else {
                                html_footer = btn_receber_peca;
                            }
                            html_body += '</ul>';
                            if (retorno[0].num === "") {
                                html_footer += btn_atribuir_num;
                            } else {
                                html_footer += btn_alterar_num;
                            }
                            if (retorno[0].bloqueado === "" || retorno[0].bloqueado === "0") {
                                html_footer += btn_bloquear;
                            } else {
                                html_footer += btn_desbloquear;
                            }

                            break;
                        default:
                            break;
                    }
                }
                $('.modal-title').html(titulo);
                $('.modal-body').html(html_body);
                $('.modal-footer').html(html_footer + '<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>');
                $('#cs-modal').modal('show');
                $('#cs-modal .cs-bloquear').click(function () {
                    $.ajax({
                        type: 'GET',
                        url: 'action/action.php',
                        dataType: 'json',
                        data: {
                            action_pagina: 'usuario-aluno',
                            action: "bloquear",
                            action_id: $(this).attr('data-id')
                        },
                        success: function (retorno) {
                            if (retorno.erro === false) {
                                alert_open("success", "Aluno bloqueado com sucesso.");
                                if (pagina === "usuario-aluno") {
                                    listar('usuario-aluno', 1, 9);
                                }
                            } else {
                                alert_open("danger", retorno.msg_erro);
                            }
                        },
                        error: function () {
                            alert_open("danger", "Erro inesperando, tente novamente mais tarde.");
                        }});
                    modal_open(id, pagina, titulo);
                });
                $('#cs-modal .cs-desbloquear').click(function () {
                    $.ajax({
                        type: 'GET',
                        url: 'action/action.php',
                        dataType: 'json',
                        data: {
                            action_pagina: 'usuario-aluno',
                            action: "desbloquear",
                            action_id: $(this).attr('data-id')
                        },
                        success: function (retorno) {
                            if (retorno.erro === false) {
                                alert_open("success", "Aluno desbloqueado com sucesso.");
                                if (pagina === "usuario-aluno") {
                                    listar('usuario-aluno', 1, 9);
                                }
                            } else {
                                alert_open("danger", retorno.msg_erro);
                            }
                        },
                        error: function () {
                            alert_open("danger", "Erro inesperando, tente novamente mais tarde.");
                        }});
                    modal_open(id, pagina, titulo);
                });
                $('#cs-modal .cs-excluir-info').click(function () {
                    switch ($(this).attr('data-pagina')) {
                        case "peca":
                            titulo = "Deseja realmente excluir a peça selecionada?";
                            html_body = "Não será possível recuperá-la.";
                            html_footer = '<button type="button" data-id="' + id + '" data-pagina="' + pagina + '" class="btn btn-primary cs-excluir">Excluir</button>';
                            break;
                        case "usuario-funcionario":
                            titulo = "Deseja realmente excluir o funcionario selecionado?";
                            html_body = "Não será possível recuperá-lo.";
                            html_footer = '<button type="button" data-id="' + id + '" data-pagina="' + pagina + '" class="btn btn-primary cs-excluir">Excluir</button>';
                            break;
                        default :
                            break;
                    }
                    $('.modal-title').html(titulo);
                    $('.modal-body').html(html_body);
                    $('.modal-footer').html(html_footer + btn_voltar + '<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>');
                    $('#cs-modal .cs-voltar').click(function () {
                        modal_open(id, pagina, titulo);
                    });
                    $('#cs-modal .cs-excluir').click(function () {
                        excluir($(this).attr('data-pagina'), $(this).attr('data-id'));
                        $('#cs-modal').modal('hide');
                    });
                });
                $('#cs-modal .cs-editar').click(function () {
                    pre_editar($(this).attr('data-pagina'), $(this).attr('data-id'));
                    $('#cs-modal').modal('hide');
                });
                $('#cs-modal .cs-reset-senha-info').click(function () {
                    titulo = "Deseja realmente resetar a senha do usuario selecionado?";
                    html_body = "Não será possível recuperar a senha anterior.";
                    html_footer = '<button type="button" data-id="' + id + '" class="btn btn-primary cs-reset-senha">Resetar</button>';
                    $('.modal-title').html(titulo);
                    $('.modal-body').html(html_body);
                    $('.modal-footer').html(html_footer + '<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>');
                    $('#cs-modal .cs-reset-senha').click(function () {
                        $.ajax({
                            type: 'GET',
                            url: 'action/action.php',
                            dataType: 'json',
                            data: {
                                action_pagina: 'usuario',
                                action: "reset_senha",
                                action_id: $(this).attr('data-id')
                            },
                            success: function (retorno) {
                                if (retorno.erro === false) {
                                    alert_open("success", "Senha resetada com sucesso.");
                                } else {
                                    alert_open("danger", retorno.msg_erro);
                                }
                            },
                            error: function () {
                                alert_open("danger", "Erro inesperando, tente novamente mais tarde.");
                            }});
                        $('#cs-modal').modal('hide');
                    });
                });
                $('#cs-modal .cs-conf-alterar-num').click(function () {
                    $.ajax({
                        type: 'GET',
                        url: 'action/action.php',
                        dataType: 'json',
                        data: {
                            action_pagina: 'numero',
                            action: "listar",
                            sexo: retorno[0].sexo
                        },
                        success: function (retorno_alterar) {
                            if (retorno_alterar.erro === false) {

                                var a = 0;
                                var html_tags = "";
                                var html_footer = "";
                                $.each(retorno_alterar, function () {
                                    a++;
                                });
                                html_tags += '<select name="novo_numero">';
                                if (retorno[0].num !== "") {
                                    html_tags += '<option value="' + retorno[0].num + '">' + retorno[0].num + '<opition>';
                                } else {
                                    html_tags += '<option value="">Selecione<opition>';
                                }
                                for (var j = 0; j < a - 1; j++) {
                                    html_tags += '<option value="' + retorno_alterar[j].num + '">' + retorno_alterar[j].num + '<opition>';
                                }
                                html_tags += '<\select>';
                                html_footer = '<button type="button" data-id="' + retorno[0].usuario + '" class="btn btn-success cs-alterar-num">Salvar</button>';
                                $('#cs-num-aluno').html(html_tags);
                                $('.modal-footer').html(html_footer + btn_voltar + '<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>');
                                $('#cs-modal .cs-voltar').click(function () {
                                    modal_open(id, pagina, titulo);
                                });
                                $('#cs-modal .cs-alterar-num').click(function () {
                                    var usuario_alterar = $(this).attr('data-id');
                                    var novo_numero = $('select[name=novo_numero]').val();
                                    $.ajax({
                                        type: 'GET',
                                        url: 'action/action.php',
                                        dataType: 'json',
                                        data: {
                                            action_pagina: 'numero',
                                            action: "editar",
                                            usuario: usuario_alterar,
                                            novo_numero: novo_numero
                                        },
                                        success: function (retorno_alterar) {
                                            if (retorno_alterar.erro === false) {
                                                modal_open(id, pagina, titulo);
                                                if (pagina === "usuario-aluno") {
                                                    listar('usuario-aluno', 1, 9);
                                                }
                                            }
                                        }
                                    });
                                });
                            }
                        }
                    });
                });

            } else {
                alert_open("danger", retorno.msg_erro);
            }
        },
        error: function () {
            alert_open("danger", "Erro ao abrir modal");
        }
    });
}
$(document).ready(function () {
    $('button.cs-pesquisar').click(function () {
        var ra = $('input[name=ra]').val();
        var numero = $('input[name=numero]').val();
        if (ra === "" && numero === "") {
            alert_open("danger", "Digite RA ou Número.");
        } else if (ra !== "") {
            modal_open(ra, $(this).attr('data-pagina'), "Detalhes do Aluno");
        } else {
            modal_open(numero, $(this).attr('data-pagina'), "Detalhes do Aluno");
        }
    });
    $('#rm-lancamento').click(function () {
        excluir('lancamento', $(this).attr('data-id'));
        location.href = caminho + 'lancamento';
    });
    $('#add-todos').click(function () {
        $('#pecas-lancamento').multiSelect('select_all');
    });
    $('#rm-todos').click(function () {
        $('#pecas-lancamento').multiSelect('deselect_all');
    });
    $('button.cs-cancelar').hide();
    $('button.cs-editar').hide();
    $('select[name=tipo]').change(function () {
        if ($(this).val() === "outro") {
            $('div.cs-outro-form').show();
        }
    });
    $('a#cs-dataGrid-funcionario').click(function () {
        listar('usuario-funcionario', 1, 9);
        $('.cs-li-aluno').removeClass('active');
        $('.cs-li-funcionario').addClass('active');
    });
    $('a#cs-dataGrid-aluno').click(function () {
        listar('usuario-aluno', 1, 9);
        $('.cs-li-funcionario').removeClass('active');
        $('.cs-li-aluno').addClass('active');
    });
    $('a#cs-dataGrid-num-m').click(function () {
        listar('numero_masculino', 1, 9);
        $('.cs-li-feminino').removeClass('active');
        $('.cs-li-masculino').addClass('active');
    });
    $('a#cs-dataGrid-num-f').click(function () {
        listar('numero_feminino', 1, 9);
        $('.cs-li-masculino').removeClass('active');
        $('.cs-li-feminino').addClass('active');
    });
    $('button.cs-salvar').click(function () {
        switch ($(this).attr('data-action')) {
            case 'cadastrar_peca':
                cadastrar_peca();
                break;
            case 'cadastrar_lancamento':
                cadastrar_lancamento();
                break;
            case 'cadastrar_idioma':
                cadastrar_idioma();
                break;
            case 'cadastrar_usuario':
                cadastrar_usuario_funcionario();
                break;
            case 'cadastrar_numero':
                cadastrar_numero();
                break;
            default:
                break;
        }
    });
    $('button.cs-atribuir-auto').click(function () {
        atribuir_numero_auto();
    });
    $('button.cs-cancelar').click(function () {
        limpar_form_cadastro();
    });
    $('button.cs-logar').click(function () {
        logar();
    });
    $('button.cs-limpar').click(function () {
        $('#cs-form input').val('');
        $('#cs-form select').val('');
    });
    $('button.close-success').click(function () {
        alert_close("success");
    });
    $('button.close-danger').click(function () {
        alert_close("danger");
    });
    $('a.cs-deslogar').click(function () {
        deslogar();
    });
});