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

        if (!key_exists('search', $get)) {
            return;
        }
        $search = $get['search'];

        if (is_array($search) && ((key_exists('query', $search) && $search['query'] !== "") ||
            (key_exists('date', $search) && $search['date'] !== ""))) {

            $this->view->registerJs($this->getJS());

            Modal::begin(
                [
                    'title' => '<div><h2>Короткая ссылка</h2><h5 id="shortLinkResult" class=""></h5></div>',
                    'id' => 'shortLinkModal',
                    'toggleButton' => [
                        'class' => 'btn btn-primary',
                        'label' => 'Короткая ссылка ★'
                    ],
                    'dialogOptions' => [
                        'class' => 'modal-fullscreen-md-down'
                    ],
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
            document.getElementById('inputShortLink3').value = "★ $this->host/"+obj.short+" ★"
            document.getElementById('shortLinkResult').innerHTML = "★&nbsp;$this->host/"+obj.short+"&nbsp;★"
          }
        }
    })   
    
    const btns = document.querySelectorAll('#createShortLinkForm button')
    // Проходим все объекты кнопок по query slector в цикле и вешаем event listener 
    btns.forEach((btn) => {
      btn.addEventListener('click', e => {
        const inp = document.querySelector("[aria-describedby='" + btn.getAttribute('id')+"']")
        let text = document.getElementById(inp.getAttribute('id'))
       
       checkButton(btn)
       
        navigator.clipboard.writeText(text.value)
          .then(() => {})
          .catch(err => {
            console.log('Something went wrong', err);
          });
      hideFunc()
      })
    })
    
    // Закрывает модальное окно 
    function hideFunc() {
        const truck_modal = document.querySelector('#shortLinkModal');
        const modal = bootstrap.Modal.getInstance(truck_modal);
        modal.hide();
    }
    
    // Меняет класс иконки у кнопки на выбранный чекбокс и отменяет ранее выбранную кнопку
    function checkButton(btn) {
      btns.forEach((butn) => {
        if (butn === btn) {
          butn.children[0].classList.remove('bi-clipboard')
          butn.children[0].classList.add('bi-clipboard-check')  
        } else {
          butn.children[0].classList.remove('bi-clipboard-check')
          butn.children[0].classList.add('bi-clipboard')  
        }
      })        
    }
    
    const inputs = document.querySelectorAll('#createShortLinkForm input')
    // Проходим все объекты input полей по query slector в цикле и вешаем event listener 
    inputs.forEach((input) => {
        input.addEventListener("focus", function() {
        this.select();
      });
    })
    
    // Действие по ctrl+c — копирование текста, закрытие окна, смена иконки кнопки
    document.addEventListener('keydown', function(event) {
      console.log(event.code)
      if (event.ctrlKey && event.code === 'KeyC') {
        const el = event.target.attributes
        const elID = el.getNamedItem('aria-describedby').value
        const btn = document.getElementById(elID)
        checkButton(btn)
        hideFunc()
      }
});

JS;

    }
}
