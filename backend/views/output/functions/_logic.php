<?php
?>
<H4>
    Условные и Логические операторы
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
            IF .. ELSE ..
        </td>
        <td>
            <code>
                if (true) {
                <br> // code
                <br>
                <br> } else {
                <br>
                <br> // code
                <br> }
            </code>
        </td>
        <td>
            <code>
                if (true) $a=2; else $a=4;
                <br>
                if ($a > 7)
                <br>
                { $a=4; $b=6;}
                <br> else <br>
                { $a=6; <br> $b=7;}
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
            <code>
                ((true) && (false)) = false;
                <br>((true) && (true)) = true;
                <br>((false) && (false)) = false;
            </code>
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
            <CODE>
                ((true) || (true)) = true;
                <br> ((true) || (false)) = true;
                <br> (false) || (false)) = false;
            </CODE>
        </td>
    </tr>
    <tr>
        <td>
            ИНВЕРСИЯ
        </td>
        <td>
            not(event)
        </td>
        <td>
            <CODE>
                not(1) = 0;
                not(0) = 1;
            </CODE>
        </td>
    </tr>
</table>
