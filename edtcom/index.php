<?php

// Inclui o arquivo de configuração global do aplicativo:
require($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

/***********************************************
 * Todo o código PHP desta página começa aqui! *
 ***********************************************/

// Se um comentário foi enviado...
if ($_SERVER["REQUEST_METHOD"] == "POST") :

    // Obtém o id do artigo original:
    if (isset($_POST['a'])) $art = intval($_POST['a']);
    else $art = 0;

    // Se o ID do artigo é '0', redireciona para a página inicial:
    if ($art == 0) header('Location: /');

    // Se usuário está logado...
    if ($user) :

        // Obtém o ID do comentário a ser apagado:
        if (isset($_POST['c'])) $id = intval($_POST['c']);
        else $id = 0;

        // Se o ID retornado é '0', redireciona para a página anterior:
        if ($id == 0) header('Location: /view/?' . $art . '#comments');

        // Obtém o comentário a ser editado, somente se é do mesmo autor e artigo:
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

            // Obtém o comentário editado:
            $comment = post_clean('b', 'string');

            // Apaga comentário:
            $sql = "UPDATE comments SET cmt_content = '{$comment}' WHERE cmt_id = '{$id}'";
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

else :

    header('Location: /');

endif;

/***********************************
 * Fim do código PHP desta página! *
 ***********************************/
