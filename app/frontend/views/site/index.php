<?php

/**
 * @var yii\web\View $this
 * @var QuestionDataProvider $results
 * @var Pagination $pages
 * @var SearchForm $model
 * @var string $errorQueryMessage
 * @var FeatureFlag $flag
 */

use App\FeatureToggle\FeatureFlag;
use App\forms\SearchForm;
use App\helpers\DateHelper;
use App\models\Comment;
use App\repositories\Question\QuestionDataProvider;
use frontend\widgets\question\CommentSummary;
use frontend\widgets\Scroll\ScrollWidget;
use frontend\widgets\search\FollowLink;
use frontend\widgets\search\FollowQuestion;
use kartik\daterange\DateRangePicker;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\data\Pagination;
use yii\helpers\Url;

$this->title = 'Поиск по ФКТ';

$this->params['meta_description'] = 'Поиск вопросов и комментариев на сайте ФКТ.';

if ($results) {
    $this->registerMetaTag(['name' => 'robots', 'content' => 'noindex, nofollow']);
    $this->params['meta_description'] = 'Результаты по запросу ' . mb_strtolower($model->query);
} else {
    $this->registerLinkTag(['rel' => 'canonical', 'href' => Yii::$app->params['frontendHostInfo']]);
    $this->registerMetaTag(['name' => 'robots', 'content' => 'index, nofollow']);
}

echo Html::beginForm(['/site/search-settings'], 'post', ['name' => 'searchSettingsForm', 'class' => 'd-flex']);
echo Html::hiddenInput('value', 'toggle');
echo Html::endForm();

$inputTemplate = '<div class="input-group mb-2">
          {input}
          <button class="btn btn-primary" type="submit" id="button-search">Поиск</button>
          <button class="btn btn-outline-secondary ' .
    (Yii::$app->session->get('show_search_settings') ? 'active' : "") . '" id="button-search-settings">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sliders" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M11.5 2a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM9.05 3a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0V3h9.05zM4.5 7a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM2.05 8a2.5 2.5 0 0 1 4.9 0H16v1H6.95a2.5 2.5 0 0 1-4.9 0H0V8h2.05zm9.45 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm-2.45 1a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0v-1h9.05z"/>
            </svg>
          </button>
          </div>';

$this->params['breadcrumbs'][] = $this->title;
?>
  <div class="site-index">
    <div class="search-block">
      <div class="container-fluid">

          <?php $form = ActiveForm::begin(
              [
                  'method' => 'GET',
                  'action' => ['site/index'],
                  'options' => ['class' => 'pb-1 mb-2 pt-3', 'autocomplete' => 'off'],
              ]
          ); ?>
        <div class="d-flex align-items-center">
            <?= $form->field($model, 'query', [
                'inputTemplate' => $inputTemplate,
                'options' => [
                    'class' => 'w-100', 'role' => 'search'
                ]
            ])->textInput(
                [
                    'type' => 'search',
                    'class' => 'form-control form-control-lg',
                    'placeholder' => "Поиск",
                    'autocomplete' => 'off',
                ]
            )->label(false); ?>
        </div>
          <div id="search-setting-panel"
               class="search-setting-panel <?= Yii::$app->session->get('show_search_settings') ? 'show-search-settings' : '' ?>">

              <?= $form->field($model, 'matching', ['inline' => true, 'options' => ['class' => 'pb-2']])
                  ->radioList($model->getMatching(), ['class' => 'form-check-inline'])
                  ->label(false); ?>

              <div class="row">
                  <div class="col-md-6">
                      <?php
                      // Widget https://demos.krajee.com/date-range
                      // timePicker https://www.daterangepicker.com/#config
                      echo DateRangePicker::widget(
                          [
                              'model' => $model,
                              'attribute' => 'date',
                              'name' => 'date_range_3',
                              'presetDropdown' => true,
                              'language' => 'ru',
                              'convertFormat' => true,
                              'pluginOptions' => [
                                  'timePicker' => true,
                                  'timePicker24Hour' => true,
//                          'timePickerIncrement' => 15,
//                          'minuteStep' => 1,
                                  'locale' => ['format' => 'd.m.Y H:i']
                              ],
                              'options' => ['placeholder' => 'За весь период'],
                              'containerOptions' => [
                                  'class' => 'mb-3 kv-drp-container'
                              ],
                              'pluginEvents' => [
                                  'apply.daterangepicker' => 'function() { $(this).closest("form").submit(); }',
                              ],
                          ],
                      ); ?>
                  </div>
                  <div class="col-md-6 d-flex align-items-center">
                      <?= $form->field($model, 'dictionary', ['options' => ['class' => 'pb-2']])
                          ->checkbox()
                          ->label('Словарь концептуальных терминов (тестирование)'); ?>
                  </div>
              </div>
          </div>
          <?php ActiveForm::end(); ?>
      </div>
    </div>
      <?php if ($flag && $flag->isEnabled('09051945B') && !$results): ?>
          <div class="denpobedy mb-3">
                  <video
                      playsinline
                      autoplay
                      muted
                      loop
                      poster="/video/denpobedy.png"
                      class="object-fit-md-contain"
                  >
                    <source
                            src="<?= Yii::$app->params['staticHostInfo']."/video/denpobedy.webm"; ?>"
                            type="video/webm"
                    />
                      <source
                          src="<?= Yii::$app->params['staticHostInfo']."/video/denpobedy.mp4"; ?>"
                          type="video/mp4"
                      />
                      Элемент video не поддерживается вашим браузером.
                      <a href="video/denpobedy.mp4">Скачайте видео поздравление с Днём Победы!</a>.
                  </video>
          </div>
      <?php endif; ?>
      <div class="container-fluid search-results">
          <?php if (!$results): ?>
              <?php if ($errorQueryMessage): ?>
                  <div class="card">
                      <div class="card-body"><?= $errorQueryMessage; ?></div>
                  </div>
              <?php endif; ?>
          <?php endif; ?>
          <?php if ($results): ?>
          <?php
          // Property totalCount пусто пока не вызваны данные модели getModels(),
          // сначала получаем массив моделей, потом получаем общее их количество
          /** @var Comment[] $comments */
          $comments = $results->getModels();
          $pagination = new Pagination(
              [
                  'totalCount' => $results->getTotalCount(),
                  'defaultPageSize' => Yii::$app->params['questions']['pageSize'],
              ]
          );
          ?>
          <div class="row">
              <div class="col-md-12">
                  <?php if ($pagination->totalCount === 0): ?>
                    <?php $this->params['meta_description'] = 'Поиск вопросов и комментариев на сайте ФКТ.'; ?>
                      <p><strong>По вашему запросу ничего не найдено</strong></p>
                  <?php else: ?>
                      <div class="row">
                          <div class="col-md-8 d-flex align-items-center">
                              <?= CommentSummary::widget(['pagination' => $pagination]); ?>
                          </div>
                          <div class="col-md-4">
                              <div class="d-flex align-items-start ">
                                  <label aria-label="Сортировка" for="input-sort"></label>
                                  <select id="input-sort" class="form-select mb-3" onchange="location = this.value;">
                        <?php
                        $values = [
                            '' => 'Сортировка по умолчанию',
                            'datetime' => 'Сначала старые комментарии',
                            '-datetime' => 'Сначала новые комментарии',
                        ];
                        $current = Yii::$app->request->get('sort');
                        ?>
                        <?php foreach ($values as $value => $label): ?>
                          <option value="<?= Html::encode(Url::current(['sort' => $value ?: null])) ?>"
                                  <?php if ($current == $value): ?>selected="selected"<?php endif; ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
            <?php endif; ?>
            <?php foreach ($comments as $comment): ?>
              <div class="card mb-4" data-entity-id="<?= $comment->data_id; ?>">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>
                            <?= DateHelper::showDateFromTimestamp($comment->datetime); ?>, <?= \App\helpers\SearchResultsHelper::showUsername($comment); ?>
                        </div>
                        <div><?= "#" . $comment->data_id; ?></div>
                    </div>
                </div>

                <div class="card-body">
                  <div class="card-text comment-text">
                  <?php if (!$comment->highlight['text'] || !$comment->highlight['text'][0]): ?>
                        <?php echo Yii::$app->formatter->asRaw(htmlspecialchars_decode($comment->text)); ?>
                    <?php else: ?>
                      <?php echo Yii::$app->formatter->asRaw(htmlspecialchars_decode($comment->highlight['text'][0])); ?>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <?= FollowQuestion::widget(['comment' => $comment, 'pagination' => $pagination]); ?>
                    <?= FollowLink::widget(['comment' => $comment]); ?>
                </div>
              </div>
            <?php endforeach; ?>

          <div class="container container-pagination">
            <div class="detachable fixed-bottom">
                <?php echo LinkPager::widget(
                    [
                        'pagination' => $pagination,
                        'firstPageLabel' => true,
                        'lastPageLabel' => true,
                        'maxButtonCount' => 3,
                        'options' => [
                            'class' => 'd-flex justify-content-center'
                        ],
                        'listOptions' => ['class' => 'pagination mb-0']
                    ]
                ); ?>
            </div>
          </div>

        </div>
      </div>
    </div>
      <?= ScrollWidget::widget(['data_entity_id' => $comment->data_id ?? 0]); ?>
      <?php endif; ?>
  </div>


<?php $js = <<<JS
  let menu = $(".search-block");
var menuOffsetTop = menu.offset().top;
var menuHeight = menu.outerHeight();
var menuParent = menu.parent();
var menuParentPaddingTop = parseFloat(menuParent.css("padding-top"));
 
checkWidth();
 
function checkWidth() {
    if (menu.length !== 0) {
      $(window).scroll(onScroll);
    }
}
 
function onScroll() {
  if ($(window).scrollTop() > menuOffsetTop) {
    menu.addClass("shadow");
    menuParent.css({ "padding-top": menuParentPaddingTop });
  } else {
    menu.removeClass("shadow");
    menuParent.css({ "padding-top": menuParentPaddingTop });
  }
}

const btn = document.getElementById('button-search-settings');
btn.addEventListener('click', toggleSearchSettings, false)

function toggleSearchSettings(event) {
  event.preventDefault();
  btn.classList.toggle('active')
  document.getElementById('search-setting-panel').classList.toggle('show-search-settings')
  
  const formData = new FormData(document.forms.searchSettingsForm);
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/site/search-settings");
  xhr.send(formData);
}

$('input[type=radio]').on('change', function() {
    $(this).closest("form").submit();
});

JS;

$this->registerJs($js);
