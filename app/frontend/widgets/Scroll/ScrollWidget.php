<?php

declare(strict_types=1);

namespace frontend\widgets\Scroll;

use yii\base\Widget;

class ScrollWidget extends Widget
{
    public string $position;
    public bool $showTop = true;
    public bool $showLast = true;

    public function run(): string
    {
        $this->view->registerJs($this->getJs());
        $str = $this->showTop ? '<div id="toTop" style="display: block;"></div>' : '';
        $str .= $this->showLast ? '<div id="toLast" style="display: block;"></div>' : '';
        return $str;
    }

    private function getJs(): string
    {
        $position = $this->position;

        $jsToTop = <<<JS
            $(document).ready(function () {
            
              //scroll on top
              $(window).scroll(function () {
                if ($(this).scrollTop() >= 160) {
                    $("#toTop").fadeIn();
                } else {
                  $("#toTop").fadeOut();
                }
              });
              var mode = (window.opera) ? ((document.compatMode == "CSS1Compat") ? $('html') : $('body')) : $('html,body');
              $('#toTop').click(function () {mode.animate({scrollTop: 0}, {duration: 200, queue: false});return false;});
            });
JS;

        $jsToLast = <<<JS
            var lastCommentElement = document.querySelector("[data-entity-id='$position']");
            var btnToLast = document.getElementById('toLast');
            
            function handleToLastCommentClick() {
               lastCommentElement.scrollIntoView({block: "start", behavior: "smooth"});
            }
          btnToLast.addEventListener('click', handleToLastCommentClick);
JS;
        $js = $this->showTop ? $jsToTop : '';
        $js .= $this->showLast ? $jsToLast : '';

        return $js;
    }
}
