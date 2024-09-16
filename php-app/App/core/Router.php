<?php

class Router
{
  public static function route_request()
  {
    if (isset($_SERVER['REQUEST_URI'])) {
      $route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

      switch ($route) {
        case '/':
          $controller = new HomepageController();
          $controller->display_homepage();
          break;
        case '/adminpanel':
          $controller = new AdminPanelController();
          $controller->display_admin_panel();
          break;
          // case "/article":
          // $controller = new ArticleController();
          // $controller->display_article();
          // break;
        default:
          header('HTTP/1.0 404 Not Found');
          echo '404 Not Found';
          break;
      }
    }
  }
}
