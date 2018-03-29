<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 25.04.2017
 * Time: 19:20
 */

namespace Course\Services\Utils;


class HTMLGenerator
{
    private $validInputTypes = ["text", "email", "password", "date", "submit"];
    private static $validMethodTypes = ["post", "get"];

    /**
     * @param int $largeColumnSize The size of the column on large displays
     * @param int $mediumColumnSize The size of the column on medium displays
     * @param int $smallColumnSize The size of the column on small displays
     */
    public static function row(int $largeColumnSize, int $mediumColumnSize = 8, int $smallColumnSize = 10)
    {
        echo "<div class='row'>
           <div class='large-$largeColumnSize medium-$mediumColumnSize small-$smallColumnSize columns'>";
    }

    /**
     * Will close the row, as it is not good to leave hanging HTML tags
     */
    public static function closeRow()
    {
        echo "</div> </div>";
    }

    public static function form(string $method, string $action, array $data, string $class = "", string $style = "", string $enctype = "", bool $disabled = false)
    {

        if (!in_array($method, self::$validMethodTypes)) {
            throw new Exceptions\MethodNotValid("Method " . $method . " is not a valid method type");
        }

        echo "<form method=$method action=$action class=$class style=$style enctype=$enctype>";

        foreach ($data as $input) {
            echo "<label>" . $input['label'] . "</label>";

            if ($input['type'] === "submit") {

                //Disable submit button if a given condition is met
                if ($disabled) {
                    echo "<input type=" . $input['type'] . " disabled class='button' name=" . $input['name'] . " value=" . $input['value'] . ">";
                } else {
                    echo "<input type=" . $input['type'] . " class='button' name=" . $input['name'] . " value=" . $input['value'] . ">";
                }
            } else {
                echo "<input type=" . $input['type'] . " name=" . $input['name'] . " value=" . $input['value'] . ">";
            }
        }

        echo "</form>";
    }

    public static function table($borderWidth, array $headers, array $rowData)
    {

        echo "<table border='" . $borderWidth . "'>";
        echo "<tr>";
        foreach ($headers as $header) {
            echo "<th>" . $header . "</th>";
        }

        echo "</tr>";

        foreach ($rowData as $data) {
            echo "<tr>";

            foreach ($data as $tableCell) {
                echo "<td>" . $tableCell . "</td>";
            }

            echo "</tr>";
        }

        echo "</table>";

    }

    public static function tag(string $tagName, string $content, string $class = "", $style = "")
    {
        return "<" . $tagName . " class='" . $class . "' style='" . $style . "'>" . $content . "</" . $tagName . ">";
    }

    public static function link(string $href, string $content, string $class = "", $style = "")
    {
        return "<a href='" . $href . "' class='" . $class . "' style='" . $style . "'>" . $content . "</a>";
    }

    public static function image(string $src, string $alt, string $class = "", string $style = "")
    {
        return "<img src='" . $src . "' alt='" . $alt . "' class='" . $class . "' style='" . $style . "'>";

    }

}