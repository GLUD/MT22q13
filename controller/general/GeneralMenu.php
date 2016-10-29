<?php

require_once(Config::PATH . Config::GENERAL . 'AdminSession.php');

/**
 * GeneralMenu
 *
 * class that generates the navigation
 *
 * @author		Harold D Duque C
 * @version		1.0
 * @since		Oct of 2016
 */
class GeneralMenu {

    /**
     * html of menu
     *
     * @var strign
     */
    private $htmlMenu;

    /**
     * @var AdminSession
     */
    private $adminSession;

    /**
     * Construct
     *
     * @param NokTemplate  $nokTemplate
     */
    public function __construct($nokTemplate) {

        $this->adminSession = new AdminSession();

        $this->htmlMenu ="
            <header>
                <nav class=\"navbar navbar-default navbar-fixed-top navbar-md\" role=\"navigation\">
                  <div class=\"container-fluid\">

                    <div class=\"navbar-header\">
                      <a class=\"navbar-brand\" href=\"?view=home\">
                          <img alt=\"Brand\" src=\"../../view/imgs/logomin.png\">
                      </a>
                      <button type=\"button\" class=\"navbar-toggle collapsed\" data-toggle=\"collapse\" data-target=\"#bs-example-navbar-collapse-1\">
                        <span class=\"sr-only\">Toggle navigation</span>
                        <span class=\"icon-bar\"></span>
                        <span class=\"icon-bar\"></span>
                        <span class=\"icon-bar\"></span>
                      </button>
                    </div>

                    <div class=\"collapse navbar-collapse\" id=\"bs-example-navbar-collapse-1\">
                      <ul class=\"nav navbar-nav\">
                        <li class=\"dropdown\">
                          <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-expanded=\"false\">Usuarios<span class=\"caret\"></span></a>
                          <ul class=\"dropdown-menu\" role=\"menu\">
                            <li><a href=\"?view=setUser\">Gestionar Usuarios</a></li>
                          </ul>
                        </li>

                        <li class=\"dropdown\">
                          <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-expanded=\"false\">Modulos<span class=\"caret\"></span></a>
                          <ul class=\"dropdown-menu\" role=\"menu\">
                            <li><a href=\"?view=musicalSecuence\" target=\"_blank\">Musical</a></li>
                            <li><a href=\"?view=#\">Logica</a></li>
                            <li><a href=\"?view=#\">Lingüística</a></li>
                          </ul>
                        </li>


                        <li class=\"dropdown\">
                          <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-expanded=\"false\">Reportes<span class=\"caret\"></span></a>
                          <ul class=\"dropdown-menu\" role=\"menu\">
                            <li><a href=\"http://localhost/ejercicioCharts/\" target=\"_blank\">Musical</a></li>
                            <li><a href=\"?view=setTask\">Logica</a></li>
                            <li><a href=\"?view=setTask\">Lingüística</a></li>
                          </ul>
                        </li>

                      </ul>
                      <ul class=\"nav navbar-nav navbar-right\">
                        <li class=\"dropdown\">
                          <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-expanded=\"false\">Perfil<span class=\"caret\"></span></a>
                          <ul class=\"dropdown-menu\" role=\"menu\">
                            <li><a href=\"?view=setAccessView\">Mi Perfil User: {$this->adminSession->GetNameUser()}</a></li>
                            <li class=\"divider\"></li>
                            <li><a href=\"?view=exit\">Salir</a></li>
                          </ul>
                        </li>
                      </ul>
                    </div>
                  </div>
                </nav>
            </header>
         ";

        $nokTemplate->asignar("generalMenu", $this->htmlMenu);
    }

}
