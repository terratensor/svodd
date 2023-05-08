<?php

declare(strict_types=1);

namespace frontend\widgets\search;

use Yii;
use yii\base\Widget;
use yii\bootstrap5\Modal;

class ShortLinkModal extends Widget
{
    private string $origin;
    private mixed $host;

    public function init(): void
    {
        $this->origin = Yii::$app->request->getAbsoluteUrl();
        $this->host = Yii::$app->params['frontendHostInfo'];
    }

    public function run(): void
    {
        $get = \Yii::$app->request->get();

        if ($get['search']['query'] !== "" || $get['search']['date'] !== "") {
            $this->view->registerJs($this->getJS());

            Modal::begin(
                [
                    'title' => '<h2>Короткая ссылка</h2>',
                    'id' => 'shortLinkModal',
                    'toggleButton' => [
                        'class' => 'btn btn-primary',
                        'label' => 'Короткая ссылка ★'
                    ],
                    'dialogOptions' => [
                        'class' => 'modal-fullscreen-md-down'
                    ],
                    'footer' => '<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Закрыть</button>',
                ]);

            echo $this->render('short_link_form');

            Modal::end();
        }
    }

    private function getJS(): string {

        return <<<JS
 const myModalEl = document.getElementById('shortLinkModal')
    myModalEl.addEventListener('show.bs.modal', event => {
        var input = document.getElementById('shortlinkcreateform-origin')    
        if (input.value === "$this->origin") {
          return
        }
        input.value = "$this->origin" 
        const formData = new FormData(document.forms.createShortLinkForm);
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "/site/short-link");
        xhr.send(formData);
        xhr.onload = function() {
          if (xhr.status !== 200) {
            console.log("Ошибка "+xhr.status +":" + xhr.statusText);
          } else {
            var obj = JSON.parse(xhr.response);      
            document.getElementById('inputShortLink1').value = "★ $this->host/"+obj.short
            document.getElementById('inputShortLink2').value = "$this->host/"+obj.short
            document.getElementById('shortLinkResult').innerText = "★ $this->host/"+obj.short
          }
        }
    })
    
    const copyBtn1 = document.getElementById('buttonInputShortLink1')
    copyBtn1.addEventListener('click', e => {
      var text = document.getElementById('inputShortLink1')
      navigator.clipboard.writeText(text.value)
          .then(() => {})
          .catch(err => {
            console.log('Something went wrong', err);
          });
    })
    
    const copyBtn2 = document.getElementById('buttonInputShortLink2')
    copyBtn2.addEventListener('click', e => {
      var text = document.getElementById('inputShortLink2')
      navigator.clipboard.writeText(text.value)
          .then(() => {})
          .catch(err => {
            console.log('Something went wrong', err);
          });
    })
    
    document.getElementById("inputShortLink1").addEventListener("focus", function() {
      this.select();
    });
    document.getElementById("inputShortLink2").addEventListener("focus", function() {
      this.select();
    });
JS;

    }
}
