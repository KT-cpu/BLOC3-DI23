<?php
// Liste des routes disponibles
$routes = [
    '/BLOC3-DI23/' => './front/home.php',
    '/BLOC3-DI23/home' => './front/home.php',

    '/BLOC3-DI23/login' => './front/login.php',
    '/BLOC3-DI23/process_login' => './front/actions/process_login.php',

    '/BLOC3-DI23/register' => './front/register.php',
    '/BLOC3-DI23/process_register' => './front/actions/process_register.php',

    '/BLOC3-DI23/account_management' => './front/account_management.php',
    '/BLOC3-DI23/process_account_management' => './front/actions/process_account_management.php',

    '/BLOC3-DI23/clubs' => './front/clubs.php',
    '/BLOC3-DI23/club' => './front/club.php',

    '/BLOC3-DI23/create_match' => './front/create_match.php',
    '/BLOC3-DI23/process_create_match' => './front/actions/process_create_match.php',

    '/BLOC3-DI23/coach_management' => './front/coach_management.php',
    '/BLOC3-DI23/match_sheet' => './front/match_sheet.php',
    '/BLOC3-DI23/coach_match_composition' => './front/coach_match_composition.php',
    '/BLOC3-DI23/process_submit_team_composition' => './front/actions/process_submit_team_composition.php',

    '/BLOC3-DI23/result_match_management' => './front/result_match_management.php',
    
    '/BLOC3-DI23/create_match_results' => './front/create_match_results.php',
    '/BLOC3-DI23/process_create_match_results' => './front/actions/process_create_match_results.php',
];

// Récupérer l'URL demandée
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Vérifier si la route existe dans le tableau
if (array_key_exists($request, $routes)) {
    require $routes[$request];
} else {
    // Si la route n'existe pas, afficher une page d'erreur 404
    http_response_code(404);
    require './front/404.php';
}
