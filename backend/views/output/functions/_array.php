<?php
?>
<H4>
    Функции работы с массивами
</H4>
<table class="table">
    <thead>
    <tr>
        <td>
            Оператор
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
            forearch
        </td>
        <td>
            <code>
                foreach ($array as $item) {
            <br>
                // код
                }
            </code>
        </td>
        <td>
            <code>
                // сумма массива $array = [2,3,4];
              <br>  $a=0;
              <br>  foreach ($array as $item) {
                <br>
                $a= $a + $item;

              <br>  }
              <br>  // $a = 9;
            </code>
        </td>
    </tr>
    <tr>
        <td>
            AND
        </td>
        <td>
            &&
        </td>
        <td>
            ((true) && (false)) = false;
            <br>((true) && (true)) = true;
            <br>((false) && (false)) = false;
        </td>
    </tr>
    <tr>
        <td>
            OR
        </td>
        <td>
            ||
        </td>
        <td>
            ((true) || (true)) = true;
            <br> ((true) || (false)) = false;
            <br> (false) || (false)) = false;
        </td>
    </tr>
</table>
