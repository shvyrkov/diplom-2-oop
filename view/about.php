<?php
include 'layout/header.php';
?>

<div class="container">
    <h1><?= $title ?></h1>
</div>
<div class="container block1">
    <h3 class="Azg1">Что это такое?</h3>
    <div class="Atxt1">
        Для разработки и проведения групповых сессий нужны <b>методы</b> и <b>инструменты групповой работы</b>. Их более 500, единой базы нет, стандартов описания тоже. Приходится, собирать свою библиотеку из книг, из личных записей с тренингов и конференций, из интернета...
    </div>
    <div class="Atxt2">
        А что если создать <b>профессиональную библиотеку в онлайн</b>, с удобной структурой и обширным каталогом методов, инструментов и технологий, с понятным структурированным описанием, с документами, файлами, слайдами, ссылками на другие ресурсы и источники, с исторической справкой и авторами? С возможностью обсуждать и общаться с коллегами-фасилитаторами, консультироваться у экспертов...
    </div>
</div>
<br>
<div class="container ">
    <div class="block2">
        <h3 class="Azg2">Как устроена библиотека?</h3>
        <div class="Atxt3">Все методы, инструменты и технологии объединены <br>в <b>семь основных этапов</b> сессии:</div>
        <ol>
            <li>
                ОТКРЫТИЕ СЕССИИ
            </li>
            <li>
                ВИДЕНИЕ БУДУЩЕГО
            </li>
            <li>
                АНАЛИЗ СИТУАЦИИ
            </li>
            <li>
                ГЕНЕРАЦИЯ ИДЕЙ
            </li>
            <li>
                ВЫБОР РЕШЕНИЯ
            </li>
            <li>
                ПЛАН ДЕЙСТВИЙ
            </li>
            <li>
                ЗАВЕРШЕНИЕ СЕССИИ
            </li>
        </ol>
        <div class="Atxt7">
            Каждый этап имеет своё цветовое обозначение.
        </div>
        <br>
        <div class="Atxt4">
            <b>Отдельно выделены два раздела библиотеки:</b>
        </div>
        <ol>
            <li>
                ОСНОВЫ ФАСИЛИТАЦИИ
                <ul class="Atxt5">
                    <i>
                        Здесь размещаются статьи и материалы по основам фасилитации:
                    </i>
                    <li>
                        Что такое фасилитация
                    </li>
                    <li>
                        Групповая динамика>
                    </li>
                    <li>
                        Компетенции фасилитатора
                    </li>
                    <li>
                        ...
                    </li>
                </ul>
            </li>
            <li>
                И ЕЩЁ...
                <div class="Atxt6">
                    Место для энерджайзеров, активностей, специализарованных методик и всего, что не входит в семь основных этапов.
                </div>
            </li>
        </ol>
        <div class="Atxt9">
            На <b>главной странице</b> отображаются все карточки с методами и инструментами. Внутри каждого этапа размещаются соответствующие карточки. Есть виды сортировки по времени добавления, по популярности, по алфавиту...
        </div>
        <br>
        <div class="Atxt8">
            <b>Карточки</b> в каталоге Библиотеки, наглядны, понятны и удобны в использовании. Содержат в себе набор информации: название, количество участников, время проведения, аннотацию и каким этапам в сессии соответствует.
        </div>
        <br>
        <div class="Akartochki"></div>
    </div>
    <div class="block3">
        <div class="Atxt10">
            По клику открывается страница с описанием метода или инструмента. К уже представленной в карточке информации на странице добавлено краткое описание, варианты применения, ссылка на модератора и время создания...
        </div>
        <br>
        <div class="Atxt11">
            Мы постарались удобно сборать разные возможные компоненты для объемного содержания методов и инструментов. Шаблон описания помогает быстро и удобно ориентироваться в информации.
        </div>
        <br>
        <div class="Atxt12">
            Обложка метода или инструмента, дублирует информацию с карточки и добавляется ссылка на модератора. Ниже расположена кнопка на комментарии и обсуждения.
        </div>
        <br>
        <div class="Atxt13">
            <b>Краткое описание.</b> Может как полностью описывать метод и способы его проведения, так и представлять только авторскую разработку.<br><br><b>Применение.</b> Список вариантов использования или назначения для этого метода.
        </div>
        <br>
        <div class="pict-card"></div>
    </div>

    <div class="block4">
        <h3 class="Azg4">Возможности</h3>
        <div class="pict-monik"></div>
        <div class="pict-user"></div>
        <div class="pict-moder"></div>
        <div class="Atxt20">
            Библиотека Фасилитатора — это сообщество, в котором каждый желающий СВОБОДНО может зарегистрироваться на сайте и стать Пользователем. <br>По желанию подать заявку и получить права и возможности Модератора в системе.
        </div>
        <div class="Atxt21">
            Регистрация в Библиотеке бесплатная и пользователем может стать каждый желающий.<br><br>После регистрации у пользователя открывается доступ к Личному Кабинету.
        </div>
        <br>
        <h4 class="Atxt22">
            Управление Личным Кабинетом.
        </h4>
        <h5 class="Atxt23">
            Пользователь
        </h5>
        <div class="Atxt24">
            - Есть Личный Кабинет и возможность редактировать информацию о себе.<br>
            - Комментировать карточки с методами и инструментами.
        </div>
        <br>
        <h5 class="Atxt25">
            Модератор
        </h5>
        <div class="Atxt26">
            - Может создавать и редактировать свои карточки с методами и инструментами в Библиотеке. <br>
            - Может редактировать и удалять комментарии к карточкам.
        </div>
    </div>
</div>
<br>
<div class="container ">
    <div class="AboutList">
        <div class="block5">
            <h3 class="Azg5">Что дальше</h3>
            <div class="Atxt30">
                <b>Библиотека фасилитатора</b> лишь начало большого пути по созданию инфраструктуры для фасилитации, рабочего кабинета фасилитатора. <br><br>Сегодня мы уже ведем разработку раздела про <b>Обучение Фасилитации</b> и большого сервиса с готовыми кейсами, сессиями под ключ и разными специальными возможностями — <b>Лабораторию Фасилитатора</b>. <br><br>Идей, как развиваться и что делать много, но мы хотим создавать этот сервис совместно, видеть слышать предложения всех заинтересованных участников в развитии Библиотеки и реагировать на предложения и идеи наших коллег, пользователей, авторов...
            </div>
            <br>
            <div class="Atxt31">
                <b><i>Пишите нам.</i></b>
            </div>
        </div>
    </div>
</div>
<br>
<?php
include 'layout/footer.php';
