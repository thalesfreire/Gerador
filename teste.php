<?php

require_once("UsuarioManager.php");


$usuarioManager = new UsuarioManager();


$usuarioManager->delete(10);



$usuario = new Usuario();


$usuario->seqUsuario = 8;
$usuario->nomUsuarioGpsgate= '2';
$usuario->dscNomUsuario= '2';
$usuario->dscEmailUsuario= '2';
$usuario->dscSenhaUsuario= '2';
$usuario->dscEndereco= '2';
$usuario->dscComplemento= '2';
$usuario->dscCep= '2';
$usuario->dscPais= '2';
$usuario->dscUf= '2';
$usuario->dscCidade= '2';
$usuario->numTelefone= '2';
$usuario->numFoneCelular= '2';
$usuario->codCpfCnpj= '2';
$usuario->datNascimento = '10/10/2010';
$usuario->staAdmin = 4;


$usuarioManager->update($usuario);

?>