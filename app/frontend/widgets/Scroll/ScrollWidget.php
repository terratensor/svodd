<?php

declare(strict_types=1);

namespace frontend\widgets\Scroll;

use yii\base\Widget;

class ScrollWidget extends Widget
{
    public string $position;

    public function run(): void
    {
        $this->view->registerJs($this->getJs());
        echo '<div id="toLast" style="display: block;"></div>';
    }

    private function getJs(): string
    {
        $position = $this->position;

        return <<<JS
            var lastCommentElement = document.querySelector("[data-entity-id='$position']");
            var btnToLast = document.getElementById('toLast');
            
            function handleToLastCommentClick() {
               lastCommentElement.scrollIntoView({block: "start", behavior: "smooth"});
            }
          btnToLast.addEventListener('click', handleToLastCommentClick);
JS;

    }
}
