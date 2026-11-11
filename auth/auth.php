<?php
session_start();

if ($_SESSION["usuario_tipo"] === "admin") {
    header("Location:/administrador.php");
    exit;
} else if ($_SESSION["cliente_tipo"] === "cliente") {
    header("Location:/home.php");
    exit;
} else {
    header("Location:/login.php");
    exit;
}