<?php
include '../php/DataBaseQuerys.php';
include_once '../php/PageMainValidation.php';
include_once '../php/validacaoPaginaELimite.php';

LoginValidation();
?>
    <!DOCTYPE HTML>
    <HTML>
    <head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width;">
    <link href="../css/mainPagesStyle.css" rel="stylesheet" type="text/css" />
    <link href="../img/logo.ico" rel="icon" type "image/x-icon" />
    <link href="../css/table.css" rel="stylesheet" type="text/css"/>
    <link href="../css/changepwd.css" rel="stylesheet" type="text/css"/>
      <link href="../css/formulario-completo.css" rel="stylesheet" type="text/css"/>
    <link href="https://fonts.googleapis.com/css?family=Lato: 100,300,400,700|Luckiest+Guy|Oxygen:300,400" rel="stylesheet" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script> 
    <title>Gerenciamento</title>
    </head>
    <body ng-app="">
    <div ng-include="'header.php'"></div>
     <div class="full-form">
        <label>Senha atual:</label>
        <input type="text">
        <label>Nova senha:</label>
        <input type="text">
        <input type="submit" value="Alterar">
    </div>
    </body>
    </html>