<?php
?>
<H4>
    Функции работы с числами
</H4>
<table class="table">
    <thead>
    <tr>
        <td>
            Функции
        </td>
        <td>
            Синтаксис
        </td>
        <td>
            Пример
        </td>
    </tr>
    </thead>

    <tr>
        <td>
            Округление
        </td>
        <td>
            round();
            <br> * - вторым параметром
            <br>указывается количество знаков после запятой
        </td>
        <td>
            <code>
                round(2.189676,2) = 2.19;
                <br>
                round(2.189676,1) = 2.2;
                <br>
                round(2.189676) = 2;
            </code>
        </td>
    </tr>
    <tr>
        <td>
            Округление до большего целого
        </td>
        <td>
            <code>ceil();</code>
        </td>
        <td><code>
                ceil(2.1) = 3;
                ceil(2.9) = 3;
            </code>
        </td>
    </tr>
    <tr>
        <td>
            Округление до меньшего целого
        </td>
        <td>
            floor();
        </td>
        <td>
            floor(2.1) = 2;
            floor(2.9) = 2;
        </td>
    </tr>
</table>
