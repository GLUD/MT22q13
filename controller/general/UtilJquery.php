<?php
require_once(Config::PATH . Config::BACKEND . 'general/vos/GeneralSearchVo.php');
require_once(Config::PATH . Config::GENERAL . 'ManageArrays.php');

/**
 * UtilJquery
 *
 * class responsible for loading the html
 *
 * @author		Harold D Duque C
 * @version		1.0
 * @since		Oct of 2016
 */
class UtilJquery {

    public $html;
    private $jqueryIni;
    private $jqueryFin;
    private $jqueryBody;
    private $javaScriptFin;
    private $javaScriptBody;
    private $namesControlsForm;

    public $nameRedirectPage;
    public $functionsJqueryArray;
    public $functionsJavaScriptArray;

    public function __construct($nameRedirectPage) {
        $this->nameRedirectPage = $nameRedirectPage;
        $this->jqueryIni = "<script>
                                (function($){
                            ";
        $this->jqueryFin = "    })(jQuery);";
        $this->javaScriptFin = "</script>";

        $this->functionsJqueryArray = array();
        $this->functionsJavaScriptArray = array();
    }

    public function PaintJQ($nokTemplate){
        $JQ_With_HTML = $this->html;

        if(isset($this->functionsJqueryArray)){
            if(is_array($this->functionsJqueryArray)){
                foreach ($this->functionsJqueryArray as $valueJQ) {
                    $this->jqueryBody .= " ".$valueJQ;
                }
            }
        }

        if(isset($this->functionsJavaScriptArray)){
            if(is_array($this->functionsJavaScriptArray)){
                foreach ($this->functionsJavaScriptArray as $valueJS) {
                    $this->javaScriptBody .= " ".$valueJS;
                }
            }
        }

        $JQ_With_HTML .= $this->jqueryIni.$this->jqueryBody.$this->jqueryFin.$this->javaScriptBody.$this->javaScriptFin;
        $nokTemplate->asignar("jquery", $JQ_With_HTML);
    }

    public function AddNameControlForm($name){
        $this->namesControlsForm[] = $name;
    }

    public function GetNameControlForIndex($index){
        return $this->namesControlsForm[$index];
    }

    public function AddFunctionJquery($function){
        $this->functionsJqueryArray[] = $function;
    }

    public function AddFunctionJavaScript($function){
        $this->functionsJavaScriptArray[] = $function;
    }

    public function ResetFunctionsJquery(){
        unset($this->functionsJqueryArray);
        unset($this->functionsJavaScriptArray);
    }

    public function CreateFunctionJqueryFJQ($nameFunction, $param, $FJQ){
        $functionIni = "function $nameFunction($param) {";
        $functionFin = "}";
        $functionFull = $functionIni.$FJQ.$functionFin;
        return $functionFull;
    }

    public function ResetControlsFormFJQ (){
        $functionsReset = "";
        if(is_array ($this->namesControlsForm)){
            foreach ($this->namesControlsForm as $valueArray) {
                if(substr($valueArray, -3) == "Ctx" || substr($valueArray, -3) == "Atx"){
                    $functionsReset .= " CleanInput(\"$valueArray\"); ";
                }else if(substr($valueArray, -3) == "Lst"){
                    $functionsReset .= " ResetSelect(\"$valueArray\"); ";
                }
            }
        }
        return $functionsReset;
    }

    public function ShowPopupFJQ ($type, $message){
        $this->html .= $this->PopupHtml ($type, $message);
        $functionPopUp = "$('#popup').bPopup({
                            easing: 'easeOutBack',
                            speed: 450,
                            transition: 'slideDown'
                        });";
        return $functionPopUp;
    }

    public function ShowPopUpWithRedirectionFJQ($type, $message){
        $this->html .= $this->PopupHtml ($type, $message);
        $functionPopUp = "$('#popup').bPopup({
                            onClose: function(){
                                ".$this->RedirectPageFJQ()."
                            },
                            easing: 'easeOutBack',
                            speed: 450,
                            transition: 'slideDown'
                        });";
        return $functionPopUp;
    }

    public function RedirectPageFJQ (){
        $url = Config::REDIRECTS . Config::GENERAL . "index.php?view=$this->nameRedirectPage";
        $functionRedirect = "  $(location).attr('href','$url');";
        return $functionRedirect;
    }

    public function RedirectPageWithDelayFJQ ($timeDelayInSecons){
        $url = Config::REDIRECCIONAMIENTOS . Config::GENERAL . "index.php?view=$this->nameRedirectPage";
        $functionRedirect = "setTimeout(function(){
                       $(location).attr('href','$url');
                     },$timeDelayInSecons);";
        return $functionRedirect;
    }

    private function ScrollTop ($time){
        $scrollTop = "$('html,body').animate({
			scrollTop: 0
                      }, $time);";
        return $scrollTop;
    }

    public function PopupHtml ($type, $message){
        $containerMessage = "";
        $popupHtml = "<div id=\"popup\" class=\"popup\">
                        <span class=\"buttonClose b-close\">
                            <span>X</span>
                        </span>";
        if($type == "Normal"){
            $containerMessage = "<div id=\"containerMessage\"><div>$message</div></div>";
        }else{
            $containerMessage = "<div id=\"containerMessage\"><div class='$type'>$message</div></div>";
        }
        $popupHtml .= $containerMessage ."</div>";
        return $popupHtml;
    }

    private function PopupWithOptionHtml($type, $message){
        $containerMessage = "";
        $popupHtml = "<div id=\"popup\" class=\"popup\">
                        <span class=\"buttonClose b-close\">
                            <span>X</span>
                        </span>";
        if($type == "Normal"){
            $containerMessage = "<div id=\"containerMessage\"><div>$message</div></div>";
        }else{
            $containerMessage = "<div id=\"containerMessage\"><div class='$type'>$message</div></div>";
        }
        $popupHtml .= $containerMessage ."</div>";
        return $popupHtml;
    }

    public function PopPupConfirmationHtml($type, $message){
        $containerMessage = "";
        $popupConfirmationHtml = "<div id=\"popupConfirmation\" class=\"popup\">
                        <span class=\"buttonClose b-close\">
                            <span>X</span>
                        </span>";
        if($type == "Normal"){
            $containerMessage = "<div id=\"containerMessage\"><div>$message</div>";
        }else{
            $containerMessage = "<div id=\"containerMessage\"><div class='$type'>$message</div>";
        }
        $buttons = "<div align=\"center\">
                    <button id=\"cancelPopPupBtn\" name=\"cancelPopPupBtn\" class=\"btn btn-danger b-close\" title=\"Cancelar\" type=\"button\">
                        <div><img width=\"30\" height=\"30\" src=\"../../view/imgs/error.png\"></div>
                        Cancelar
                    </button>
                    <button id=\"acceptPopPupBtn\" name=\"acceptPopPupBtn\" onclick=\"document.form.submit();\" class=\"btn btn-success\" title=\"Aceptar\" type=\"button\">
                        <div><img width=\"30\" height=\"30\" src=\"../../view/imgs/exito.png\"></div>
                        Aceptar
                    </button>
                    </div>";
        $popupConfirmationHtml .= $containerMessage .$buttons."</div></div>";
        return $popupConfirmationHtml;
    }

    public function LogNames(){
        print_r($this->namesControlsForm);
    }

}
