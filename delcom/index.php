<?php

// Inclui o arquivo de configuração global do aplicativo:
require($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

/***********************************************
 * Todo o código PHP desta página começa aqui! *
 ***********************************************/

// Obtém o id do artigo original:
if (isset($_GET['a'])) $art = intval($_GET['a']);
else $art = 0;

// Se o ID do artigo é '0', redireciona para a página inicial:
if ($art == 0) header('Location: /');

// Se usuário está logado...
if ($user) :

    // Obtém o ID do comentário a ser apagado:
    if (isset($_GET['c'])) $id = intval($_GET['c']);
    else $id = 0;

    // Se o ID retornado é '0', redireciona para a página anterior:
    if ($id == 0) header('Location: /view/?' . $art . '#comments');

    // Obtém o comentário a ser apagado, somente se é do mesmo autor e artigo:
    $sql = <<<SQL

SELECT cmt_id FROM comments 
WHERE cmt_id = '{$id}' 
    AND cmt_article = '{$art}' 
    AND cmt_author = '{$user['id']}' 
    AND cmt_status = 'on';
    
SQL;
    $res = $conn->query($sql);

    // Se o comentário é válido...
    if ($res->num_rows == 1) :

        // Apaga comentário:
        $sql = "UPDATE comments SET cmt_status = 'deleted' WHERE cmt_id = '{$id}'";
        $conn->query($sql);

        // Redireciona para o artigo original:
        header('Location: /view/?' . $art . '#comments');

    // Se não...
    else :

        // Redireciona para o artigo original:
        header('Location: /view/?' . $art . '#comments');

    endif;

// Se não está logado...
else :

    // Redireciona para o artigo original:
    header('Location: /view/?' . $art);

endif;

/***********************************
 * Fim do código PHP desta página! *
 ***********************************/
